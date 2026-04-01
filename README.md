# Readify - Sistem Perpustakaan Digital

Aplikasi perpustakaan digital untuk manajemen e-book dengan fitur berlangganan, pembacaan PDF, dan laporan penjualan.

---

## 📚 Tentang Aplikasi

**Readify** adalah aplikasi untuk mengelola perpustakaan digital berbasis web. Aplikasi ini dilengkapi dengan:
- Sistem login untuk admin dan member
- Koleksi e-book yang bisa dibaca langsung di browser
- Manajemen langganan dan pembayaran
- Pelacakan riwayat membaca
- Laporan langganan dan data buku
- Invoice otomatis dalam format PDF

---

## ✨ Fitur

### Untuk Member (Anggota)
- ✅ Daftar dan login akun
- ✅ Cari dan jelajahi koleksi buku
- ✅ Baca buku langsung dengan PDF reader
- ✅ Tandai buku favorit
- ✅ Lihat riwayat membaca
- ✅ Langganan paket membership
- ✅ Lihat dan download invoice

### Untuk Admin
- ✅ Dashboard dengan statistik
- ✅ Tambah, edit, hapus buku
- ✅ Lihat data member
- ✅ Lihat laporan langganan
- ✅ Konfirmasi transaksi berlangganan
- ✅ Export laporan ke PDF

---

## 🛠️ Teknologi

- **Backend:** Laravel 12, PHP 8.2+
- **Frontend:** Livewire 4, Tailwind CSS, Flowbite
- **Database:** MySQL 8.0+
- **PDF Reader:** PDF.js
- **Export PDF:** laravel-dompdf
- **Build Tool:** Vite, npm

---

## 📦 Instalasi

### Prasyarat
- PHP 8.2 atau lebih tinggi
- MySQL 8.0 atau lebih tinggi
- Composer
- Node.js 18+
- npm

### Langkah Instalasi

**1. Clone repository**
```bash
git clone https://github.com/Padlan123/eLibrary.git
cd eLibrary
```

**2. Install dependencies**
```bash
composer install
npm install
```

**3. Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

**4. Konfigurasi database di `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=elibrary
DB_USERNAME=root
DB_PASSWORD=
```

**5. Jalankan migrasi database**
```bash
php artisan migrate
```

**6. Build frontend**
```bash
npm run build
```

**Atau jalankan setup lengkap sekaligus:**
```bash
composer run setup
```

---

## 🚀 Cara Menjalankan

### Mode Pengembangan
```bash
npm run dev
```

Aplikasi akan berjalan di `http://localhost:8000`

### Akses Aplikasi
- **Landing Page:** `http://localhost:8000/Readify/home`
- **Login Member:** `http://localhost:8000/Readify/login`
- **Dashboard Member:** `http://localhost:8000/Readify/anggota/home`
- **Dashboard Admin:** `http://localhost:8000/Readify/admin/dashboard`

---

## 📁 Struktur Folder

```
eLibrary/
├── app/
│   ├── Models/              # Model database
│   └── Http/
│       ├── Controllers/     # Controller
│       └── Middleware/      # Middleware   
├── resources/
│   ├── views/               # Template HTML
│   └── css/                 # Style Tailwind
├── routes/
│   └── web.php              # Route aplikasi
├── database/
│   ├── migrations/          # Schema database
│   └── seeders/             # Data dummy
├── public/                  # File publik
└── config/                  # Konfigurasi
```

---

## 👥 Peran Pengguna

### Admin
- Akses penuh ke dashboard admin
- Kelola buku, member, dan subscripsi
- Lihat laporan dan statistik

### Member (Anggota)
- Akses halaman member
- Baca buku dan lihat detail
- Langganan paket
- Lihat invoice

---

## 🔐 Keamanan

- ✅ Sistem login dengan Laravel Authentication
- ✅ Proteksi CSRF otomatis
- ✅ Password di-hash dengan bcrypt
- ✅ Role-based access control
- ✅ Session aman berbasis database

---

## 📚 Fitur Utama

### Pembaca PDF
- Baca buku langsung di browser
- Navigasi halaman mudah
- Tracking halaman terakhir dibaca

### Manajemen langganan
- Berbagai paket pilihan
- Track status langganan
- History transaksi
- Invoice dapat didownload sebagai PDF

### Laporan
- Laporan berlangganan
- Data buku
- Export ke PDF

---

## 📧 Support

Ada pertanyaan atau masalah? Buat issue di repository ini.

---

**Dibuat dengan ❤️ oleh tim development**
