<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $book->title }}</title>
    @vite(['resources/css/app.css'])
    <style>
        body {
            margin: 0;
            background: #1a1a2e;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        #pdf-container {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        #pdf-canvas {
            max-width: 100%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        #toolbar {
            background: #16213e;
            color: white;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: between;
            gap: 12px;
        }
    </style>
</head>

<body>

    {{-- TOOLBAR --}}
    <div id="toolbar" class="flex items-center justify-between gap-3 px-4 py-3 bg-gray-900 text-white">
        <a href="{{ url()->previous() }}" class="text-sm text-gray-400 hover:text-white transition">
            ← Kembali
        </a>

        <div class="flex items-center gap-3">
            <button id="prev-page" class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 text-sm">‹ Prev</button>
            <span class="text-sm">
                Halaman <span id="current-page">{{ $history->last_page }}</span> / <span id="total-pages">-</span>
            </span>
            <button id="next-page" class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 text-sm">Next ›</button>
        </div>

        {{-- Progress --}}
        <div class="flex items-center gap-2 text-sm text-gray-400">
            <div class="w-24 h-2 bg-gray-700 rounded-full overflow-hidden">
                <div id="progress-bar" class="h-full bg-blue-500 transition-all"
                    style="width: {{ $history->progress_percent }}%"></div>
            </div>
            <span id="progress-text">{{ $history->progress_percent }}%</span>
        </div>
    </div>

    {{-- PDF CANVAS --}}
    <div id="pdf-container" class="flex-1 overflow-y-auto flex flex-col items-center py-6 px-4 bg-gray-900">
        <canvas id="pdf-canvas" class="shadow-xl rounded"></canvas>
    </div>

    <script type="module">
        import * as pdfjsLib from '/vendor/pdf.js/build/generic/build/pdf.mjs';
        pdfjsLib.GlobalWorkerOptions.workerSrc = '/vendor/pdf.js/build/generic/build/pdf.worker.mjs';

        const BOOK_ID = {{ $book->id }};
        const PDF_URL = '{{ Storage::url($book->pdf_file_name) }}';
        const LAST_PAGE = {{ $history->last_page }};
        const TOTAL_PAGES = {{ $book->total_pages }};
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

        let pdfDoc = null;
        let currentPage = LAST_PAGE;
        let totalPages = TOTAL_PAGES; // ← langsung dari DB, tidak perlu tunggu pdf.numPages
        let isRendering = false;
        let saveTimeout = null; // ← hanya sekali

        const canvas = document.getElementById('pdf-canvas');
        const ctx = canvas.getContext('2d');
        const currentEl = document.getElementById('current-page');
        const totalEl = document.getElementById('total-pages');
        const progressBar = document.getElementById('progress-bar');
        const progressTxt = document.getElementById('progress-text');

        // Set total halaman di UI langsung dari DB tanpa tunggu PDF load
        totalEl.textContent = totalPages;

        // Load PDF — hanya sekali dengan lazy loading
        pdfjsLib.getDocument({
            url: PDF_URL,
            rangeChunkSize: 65536,
            disableAutoFetch: true,
            disableStream: false,
        }).promise.then(pdf => {
            pdfDoc = pdf;
            renderPage(currentPage);
        });

        // Cache halaman
        const pageCache = new Map();

        async function preloadPage(pageNum) {
            if (pageNum < 1 || pageNum > totalPages) return;
            if (pageCache.has(pageNum)) return;
            const page = await pdfDoc.getPage(pageNum);
            pageCache.set(pageNum, page);
        }

        function getScale(page) {
            const container = document.getElementById('pdf-container');
            const containerWidth = container.clientWidth - 32; // 32px = padding kiri + kanan
            const viewport = page.getViewport({
                scale: 1
            }); // scale 1 dulu untuk dapat lebar asli
            const isPhone = window.innerWidth <= 425;
            const isTablet = window.innerWidth < 1024;

            if (isPhone) {
                const ratio = 0.92;
                return (containerWidth / viewport.width) * ratio;
            } else if (isTablet) {
                const ratio = 0.50;
                return (containerWidth / viewport.width) * ratio;
            } else {
                const maxWidth = window.innerWidth / 2.5;
                return maxWidth / viewport.width;
            }
        }

        // Render halaman
        async function renderPage(pageNum) {
            if (isRendering) return;
            isRendering = true;

            const page = pageCache.has(pageNum) ?
                pageCache.get(pageNum) :
                await pdfDoc.getPage(pageNum);

            const scale = getScale(page); // ← dynamic scale
            const viewport = page.getViewport({
                scale
            });

            canvas.width = viewport.width;
            canvas.height = viewport.height;

            await page.render({
                canvasContext: ctx,
                viewport
            }).promise;

            isRendering = false;
            currentEl.textContent = pageNum;

            const percent = Math.round((pageNum / totalPages) * 100);
            progressBar.style.width = percent + '%';
            progressTxt.textContent = percent + '%';

            document.getElementById('pdf-container').scrollTo(0, 0);

            preloadPage(pageNum + 1);
            preloadPage(pageNum - 1);

            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => saveProgress(pageNum), 1000);
        }

        let resizeTimeout = null;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                pageCache.clear(); // clear cache karena scale berubah
                renderPage(currentPage);
            }, 300); // debounce 300ms
        });


        // Simpan progress
        function saveProgress(page) {
            console.log('Saving progress:', {
                book_id: BOOK_ID,
                last_page: page
            });

            fetch('{{ route('anggota.reading.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                    },
                    body: JSON.stringify({
                        book_id: BOOK_ID,
                        last_page: page,
                    }),
                })
                .then(res => {
                    console.log('Status:', res.status); // cek status code
                    return res.text(); // ambil sebagai text dulu
                })
                .then(text => {
                    console.log('Raw response:', text); // lihat isi response
                })
                .catch(err => console.error('Fetch error:', err));
        }

        // Navigasi tombol
        document.getElementById('prev-page').addEventListener('click', () => {
            if (currentPage <= 1) return;
            currentPage--;
            renderPage(currentPage);
        });

        document.getElementById('next-page').addEventListener('click', () => {
            if (currentPage >= totalPages) return;
            currentPage++;
            renderPage(currentPage);
        });

        // Navigasi keyboard
        document.addEventListener('keydown', e => {
            if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderPage(currentPage);
                }
            }
            if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                if (currentPage > 1) {
                    currentPage--;
                    renderPage(currentPage);
                }
            }
        });

        // Simpan saat meninggalkan halaman — hanya sekali
        window.addEventListener('beforeunload', () => saveProgress(currentPage));
    </script>
</body>

</html>
