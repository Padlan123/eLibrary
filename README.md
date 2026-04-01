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

## 📦 Instalasi dengan Laragon

### Prasyarat
- **Laragon** sudah terinstall dengan versi terbaru

> **Catatan:** Laragon adalah all-in-one development environment yang sudah include:
> - PHP 8.2+
> - MySQL / MariaDB
> - Apache / Nginx
> - Composer
> - Node.js & npm
> - Git

Jadi tidak perlu install Composer, Node.js, atau npm secara terpisah!

### Langkah Instalasi

**1. Clone repository ke folder Laragon**
```bash
# Buka Terminal Laragon (Klik Menu Laragon > Terminal)
cd C:\laragon\www
git clone https://github.com/Padlan123/eLibrary.git
cd eLibrary
```

**2. Install dependencies PHP dan npm**
```bash
# Masih di terminal Laragon
composer install
npm install
```

**3. Setup environment file**
```bash
# Copy file environment
copy .env.example .env

# Generate app key
php artisan key:generate
```

**4. Database sudah siap (default Laragon)**

File `.env` sudah dikonfigurasi untuk Laragon:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=elibrary
DB_USERNAME=root
DB_PASSWORD=
```

Jika MySQL config Laragon berbeda, sesuaikan di `.env`

**5. Setup database & seeder**
```bash
# Jalankan migrasi dan seeding (pertama kali)
php artisan migrate --seed

```

**6. Build frontend assets (Opsional pada setup awal)**
```bash
npm run build
```

✅ **Instalasi selesai!**

> ⚠️ **PENTING:** Selalu gunakan **Terminal Laragon** (bukan CMD/PowerShell biasa), karena Laragon otomatis set PATH untuk PHP dan Composer

---

## 🚀 Cara Menjalankan

### 1. Mulai Laragon
- Buka aplikasi **Laragon**
- Klik tombol **Start All** untuk menjalankan Apache dan MySQL
- Pastikan kedua service sudah berjalan (icon berubah hijau ✅)

### 2. Jalankan Development Mode

Buka **2 Terminal Laragon** (Klik Menu Laragon > Terminal):

**Terminal 1 - Navigasi ke project**
```bash
cd C:\laragon\www\eLibrary
code .
```

Biarkan di sini untuk nanti.

**Terminal 2 - Jalankan Vite Development Server**
```bash
cd C:\laragon\www\eLibrary
npm run dev
```

Tunggu sampai Vite siap (akan muncul pesan seperti "Local: http://localhost:5173")

Biarkan kedua terminal tetap running.

### 3. Akses Aplikasi di Browser

Buka browser dan kunjungi URLs berikut:

| Halaman | URL |
|---------|-----|
| Landing Page | `http://localhost/eLibrary/` |
| Login | `http://localhost/eLibrary/login` |

---

## 🧪 Akun Test (Seeder)

### Admin
```
Email:    alex123@gmail.com
Password: alex123
Username: Alex
```

### Anggota (Member)
```
Email:    padlan123@gmail.com
Password: padlan123
Username: Padlan padilah
```

> **Info:** Ada 4 akun anggota tambahan lainnya di database untuk testing. Cek detail di `database/seeders/DatabaseSeeder.php`


---

### 📁 Struktur Folder

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
