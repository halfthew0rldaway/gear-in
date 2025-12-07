# Gear-In E-Commerce Platform

**Tugas Besar Mata Kuliah Pemrograman Web Lanjut**

Platform e-commerce modern berbasis web yang dibangun menggunakan Laravel 12, dilengkapi dengan katalog produk, keranjang belanja, manajemen pesanan, sistem review, wishlist, dan dashboard administrasi.

> **Catatan:** Project ini dikembangkan sebagai tugas besar untuk mata kuliah Pemrograman Web Lanjut. Fitur-fitur yang tersedia masih dalam tahap formalitas dan fungsionalitas dasar untuk memenuhi requirements tugas. Meskipun demikian, struktur dan kode yang ada dapat dikembangkan lebih lanjut untuk keperluan production dan real-world implementation.

## 📋 Daftar Isi

- [Prasyarat Sistem](#prasyarat-sistem)
- [Instalasi](#instalasi)
- [Konfigurasi Database](#konfigurasi-database)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Akun Default](#akun-default)
- [Fitur Aplikasi](#fitur-aplikasi)
- [Troubleshooting](#troubleshooting)
- [Informasi Teknis](#informasi-teknis)

## 🔧 Prasyarat Sistem

Sebelum melakukan instalasi, pastikan sistem Anda telah memenuhi persyaratan berikut:

### Software yang Diperlukan

1. **PHP 8.2 atau lebih tinggi**
   - Verifikasi versi: `php -v`
   - Unduh: [PHP Downloads](https://www.php.net/downloads.php)
   - Ekstensi yang diperlukan: `pdo`, `pdo_sqlite`, `mbstring`, `xml`, `ctype`, `json`, `openssl`, `tokenizer`, `fileinfo`

2. **Composer** (PHP Dependency Manager)
   - Verifikasi versi: `composer --version`
   - Unduh: [Composer Downloads](https://getcomposer.org/download/)

3. **Node.js 18.x atau lebih tinggi** (untuk frontend assets)
   - Verifikasi versi: `node -v`
   - Unduh: [Node.js Downloads](https://nodejs.org/)

4. **npm** (termasuk dalam instalasi Node.js)
   - Verifikasi versi: `npm -v`

### Software Opsional

- **Git** (untuk version control)
- Code editor (VS Code, PhpStorm, dll)

## 📦 Instalasi

### Langkah 1: Persiapan Project

Jika project berada di repository Git, lakukan clone:

    git clone <repository-url>
    cd gear-in

Atau jika project sudah tersedia, navigasi ke direktori project:

    cd gear-in


### Langkah 2: Instalasi Dependencies PHP

Jalankan perintah berikut untuk menginstal semua package Laravel dan PHP yang diperlukan:

    composer install

**Catatan:** Jika terjadi masalah memori selama instalasi, gunakan:

    COMPOSER_MEMORY_LIMIT=-1 composer install


### Langkah 3: Instalasi Dependencies Node.js

Jalankan perintah berikut untuk menginstal dependencies frontend (Vite, Tailwind CSS, dll):

    npm install


### Langkah 4: Konfigurasi Environment

1. Salin file environment:

    copy .env.example .env

   (Linux/Mac: `cp .env.example .env`)

2. Generate application key:

    php artisan key:generate
   

3. **Konfigurasi Database:**
   
   Aplikasi secara default menggunakan SQLite. File `.env` sudah dikonfigurasi dengan pengaturan berikut:

    DB_CONNECTION=sqlite
    DB_DATABASE=C:\absolute\path\to\database\database.sqlite
    SESSION_DRIVER=file
   
   
   **Untuk SQLite (Default):**
   - Pastikan path di `DB_DATABASE` menggunakan absolute path (full path)
   - File database akan dibuat otomatis pada langkah berikutnya
   
   **Alternatif MySQL:**
   Jika Anda lebih memilih menggunakan MySQL, ubah konfigurasi di `.env`:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=gearin
    DB_USERNAME=root
    DB_PASSWORD=
   

## 🗄️ Konfigurasi Database

### Langkah 1: Membuat File Database SQLite

File database seharusnya sudah tersedia di `database/database.sqlite`. Jika belum, buat file tersebut:

**Windows:**

    type nul > database\database.sqlite


**Linux/Mac:**

    touch database/database.sqlite


### Langkah 2: Menjalankan Migrations

Jalankan perintah berikut untuk membuat semua tabel database yang diperlukan:

    php artisan migrate


Output yang diharapkan:

INFO  Running migrations.

  2025_11_17_063640_create_categories_table ..................... DONE
  2025_11_17_063644_create_products_table ....................... DONE
  2025_11_17_063647_create_cart_items_table ..................... DONE
  ...


### Langkah 3: Seeding Database

Jalankan perintah berikut untuk mengisi database dengan data sample:

    php artisan db:seed


Output yang diharapkan:

INFO  Seeding database.

  Database\Seeders\CategorySeeder ................................. DONE
  Database\Seeders\ProductSeeder .................................. DONE


**Data yang akan di-seed:**
- Kategori produk
- Sample produk dengan spesifikasi detail
- Akun admin dan customer default (lihat [Akun Default](#akun-default))

### Langkah 4: Verifikasi Setup Database

Untuk memverifikasi bahwa setup database berhasil, jalankan:

    php artisan tinker


Kemudian di console tinker:

    DB::table('users')->count();  // Seharusnya mengembalikan 3
    DB::table('products')->count();  // Seharusnya mengembalikan beberapa produk
    exit


## 🚀 Menjalankan Aplikasi

### Langkah 1: Build Frontend Assets

**Untuk Development (dengan hot reload):**

    npm run dev


Biarkan terminal ini tetap berjalan. Perintah ini akan memantau perubahan file dan secara otomatis melakukan rebuild assets.

**Untuk Production (build sekali):**

    npm run build


### Langkah 2: Menjalankan Development Server

Buka **terminal window baru** (biarkan `npm run dev` tetap berjalan jika Anda menggunakannya):

    php artisan serve


Output yang diharapkan:

INFO  Server running on [http://127.0.0.1:8000]


### Langkah 3: Mengakses Aplikasi

Buka web browser Anda dan navigasi ke:

    http://127.0.0.1:8000


**Akses dari Device Lain:**
Untuk mengakses aplikasi dari device lain di network yang sama:

    php artisan serve --host=0.0.0.0

Kemudian akses via: `http://your-ip-address:8000`

## 👤 Akun Default

Setelah proses seeding selesai, Anda dapat menggunakan akun berikut untuk login:

### Akun Administrator

**Admin 1:**
- **Email:** `admin@gear-in.dev`
- **Password:** `password`
- **Nama:** Gear-In Admin
- **Akses:** Full admin panel (products, categories, orders management, reviews)

**Admin 2:**
- **Email:** `admin2@gear-in.dev`
- **Password:** `password`
- **Nama:** Gear-In Admin 2
- **Akses:** Full admin panel (products, categories, orders management, reviews)

### Akun Customer

- **Email:** `customer@gear-in.dev`
- **Password:** `password`
- **Akses:** Fitur customer (browse, cart, checkout, orders, reviews, wishlist)

## ✨ Fitur Aplikasi

> **Catatan Penting:** Fitur-fitur yang tercantum di bawah ini dikembangkan sebagai bagian dari tugas kuliah semester 5. Implementasi yang ada masih dalam tahap formalitas dan fungsionalitas dasar untuk memenuhi requirements tugas. Beberapa fitur mungkin belum memiliki validasi atau error handling yang lengkap seperti pada aplikasi production-ready. Meskipun demikian, struktur kode dan arsitektur yang digunakan sudah dirancang dengan baik dan dapat dikembangkan lebih lanjut untuk keperluan real-world implementation dan production deployment.

### Fitur Customer

- ✅ **Katalog Produk** dengan pencarian dan filter
- ✅ **Keranjang Belanja** dengan dukungan product variants
- ✅ **Proses Checkout** dengan multiple shipping options
- ✅ **Manajemen Pesanan** dengan kategori (On Going, Dibatalkan, Selesai)
- ✅ **Order Tracking** dengan tracking number
- ✅ **Pembatalan Pesanan** (untuk pesanan dengan status pending/paid)
- ✅ **Review dan Rating Produk** (hanya untuk pesanan yang sudah complete)
- ✅ **Review dari Halaman Detail Order**
- ✅ **Wishlist/Favorites**
- ✅ **Printable Receipts** (customer dan admin)
- ✅ **Product Image Carousel** dengan auto-slide (10 detik) dan navigasi manual
- ✅ **Multiple Product Images** (maksimal 10 gambar per produk)
- ✅ **Default Placeholder** untuk produk tanpa gambar
- ✅ **Sold Out Indicator** untuk produk dengan stok 0
- ✅ **Chat Widget Floating** dengan animasi dan notifikasi
- ✅ **Chat Langsung di Widget** tanpa perlu reload halaman
- ✅ **Riwayat Chat** dengan grouping per tanggal

### Fitur Administrator

- ✅ **Dashboard** dengan statistics dan charts
- ✅ **Manajemen Produk** (CRUD) dengan multiple images
- ✅ **Image Upload** dengan auto resize & crop (800x800 square)
- ✅ **Manajemen Kategori**
- ✅ **Manajemen Pesanan** dengan timeline
- ✅ **Update Status Pesanan** (auto-update payment_status saat status diubah ke paid)
- ✅ **Manajemen Tracking Number**
- ✅ **Low Stock Alerts**
- ✅ **Manajemen Review** dan admin reply
- ✅ **Chat Widget Floating untuk Admin** dengan daftar percakapan
- ✅ **Balas Chat Langsung dari Widget** tanpa reload
- ✅ **Activity Logging**

### Fitur Teknis

- ✅ **Product Variants** (size, color, switches, dll)
- ✅ **Multiple Product Images** dengan carousel
- ✅ **Stock Management** dengan variant support
- ✅ **Activity Logging**
- ✅ **Role-based Access Control**
- ✅ **Responsive Design** untuk mobile dan desktop
- ✅ **Image Processing** dengan Intervention Image
- ✅ **Auto Image Resize & Crop** untuk konsistensi
- ✅ **Product Specifications** dengan format JSON
- ✅ **Real-time Chat System** dengan auto-refresh (polling)
- ✅ **Chat Widget Floating** untuk customer dan admin

## 💬 Fitur Chat System

### Cara Kerja Chat System

Sistem chat di Gear-In menggunakan **database-based messaging** dengan **auto-refresh (polling)** untuk memberikan pengalaman yang mirip real-time tanpa memerlukan WebSocket atau service eksternal.

#### Arsitektur

1. **Database Structure:**
   - `conversations` table: Menyimpan percakapan antara customer dan admin
   - `messages` table: Menyimpan pesan-pesan dalam setiap percakapan
   - Setiap percakapan memiliki `user_id` (customer) dan `admin_id` (admin yang menangani)
   - Status percakapan: `open`, `pending`, atau `closed`

2. **Auto-Refresh Mechanism:**
   - JavaScript polling setiap 3 detik untuk mengambil pesan baru
   - Refresh otomatis setelah mengirim pesan
   - Berhenti saat tab tidak aktif (menghemat resource)
   - Menggunakan AJAX untuk komunikasi tanpa reload halaman

3. **Widget Floating:**
   - **Customer Widget:** Muncul di pojok kanan bawah dengan animasi floating
   - **Admin Widget:** Sama seperti customer, dengan daftar percakapan terbaru
   - Badge notifikasi menampilkan jumlah pesan belum dibaca
   - Auto-load percakapan terakhir saat widget dibuka

#### Flow Customer

1. Customer klik widget floating di pojok kanan bawah
2. Widget terbuka dan auto-load percakapan terakhir (jika ada)
3. Customer bisa langsung mengetik dan mengirim pesan via AJAX
4. Pesan muncul langsung tanpa reload halaman
5. Auto-refresh setiap 3 detik untuk melihat balasan admin
6. Badge notifikasi update otomatis saat ada pesan baru

#### Flow Admin

1. Admin klik widget floating di pojok kanan bawah
2. Widget menampilkan daftar 5 percakapan terbaru
3. Admin klik percakapan untuk membuka chat
4. Admin bisa langsung membalas via AJAX
5. Auto-refresh setiap 3 detik untuk melihat pesan baru dari customer
6. Badge notifikasi menampilkan total pesan belum dibaca dari semua customer

#### Halaman Chat Lengkap

- **Customer:** `/chat` - Menampilkan riwayat semua percakapan yang dikelompokkan per tanggal
  - Grouping: "Hari Ini", "Kemarin", nama hari, atau tanggal lengkap
  - Setiap percakapan menampilkan preview pesan terakhir, status, dan jumlah pesan
  - Badge unread count untuk percakapan dengan pesan belum dibaca

- **Admin:** `/admin/chat` - Menampilkan semua percakapan dari semua customer
  - Daftar lengkap dengan pagination
  - Filter dan search (jika diperlukan)
  - Status percakapan dan admin yang menangani

#### Kelebihan Pendekatan Ini

✅ **Simple:** Tidak perlu setup WebSocket/Pusher  
✅ **Reliable:** Data tersimpan di database  
✅ **College Project Friendly:** Mudah dijelaskan dan dipahami  
✅ **Functional:** Tetap memberikan pengalaman chat yang baik  
✅ **Scalable:** Bisa di-upgrade ke real-time nanti jika diperlukan  

#### Upgrade ke Real-Time (Opsional)

Jika ingin upgrade ke real-time di masa depan:
1. Install Laravel Echo + Pusher/Soketi
2. Setup broadcasting
3. Ganti polling dengan WebSocket listener
4. Database structure tetap sama, hanya mekanisme update yang berubah

## 🔍 Troubleshooting

### Masalah: "Class not found" atau "Autoload error"

**Solusi:**

    composer dump-autoload


### Masalah: "Database file does not exist"

**Solusi:**
1. Pastikan file `database/database.sqlite` sudah ada
2. Periksa file permissions (harus readable/writable)
3. Verifikasi path di `.env` menggunakan absolute path

### Masalah: "Migration error" atau "Table already exists"

**Solusi:**

    php artisan migrate:fresh --seed


**Peringatan:** Perintah ini akan menghapus semua data dan membuat ulang database!

### Masalah: "Assets not loading" atau "404 on CSS/JS files"

**Solusi:**
1. Pastikan `npm run dev` sedang berjalan (untuk development)
2. Atau jalankan `npm run build` (untuk production)
3. Clear cache: `php artisan cache:clear`
4. Clear config: `php artisan config:clear`

### Masalah: "Session driver [database] not supported"

**Solusi:**
Periksa file `.env` Anda:

    SESSION_DRIVER=file


### Masalah: "Permission denied" pada database file

**Solusi (Linux/Mac):**

    chmod 664 database/database.sqlite
    chmod 775 database


**Solusi (Windows):**
- Klik kanan pada file `database.sqlite`
- Properties > Security
- Pastikan user Anda memiliki Read & Write permissions

### Masalah: Port 8000 sudah digunakan

**Solusi:**
Gunakan port yang berbeda:

    php artisan serve --port=8001


### Masalah: "Vite manifest not found"

**Solusi:**

    npm run build


### Masalah: Tidak bisa login dengan akun default

**Solusi:**
Re-seed database:

    php artisan db:seed


Atau buat user baru secara manual:

    php artisan tinker


    \App\Models\User::create([
    'name' => 'Test User',
    'email' => 'test@test.com',
    'password' => bcrypt('password'),
    'role' => 'customer'
]);
    exit


## 📝 Perintah Tambahan

### Clear All Caches

    php artisan optimize:clear


### View Routes

    php artisan route:list


### Run Tests (jika tersedia)

    php artisan test


### Check Application Status

    php artisan about


## 🎓 Panduan untuk Evaluator

### Ringkasan Setup Cepat

1. **Instalasi dependencies:**
   
   composer install
   npm install
   

2. **Konfigurasi environment:**
   
   copy .env.example .env
   php artisan key:generate
   

3. **Setup database:**
   
   type nul > database\database.sqlite
   php artisan migrate
   php artisan db:seed
   

4. **Build assets:**
   
   npm run build
   

5. **Jalankan server:**
   
   php artisan serve
   

6. **Akses aplikasi:**
   - URL: `http://127.0.0.1:8000`
   - Login sebagai admin: `admin@gear-in.dev` / `password` atau `admin2@gear-in.dev` / `password`
   - Login sebagai customer: `customer@gear-in.dev` / `password`

### System Requirements yang Terpenuhi

- ✅ Modern PHP framework (Laravel 12)
- ✅ Database integration (SQLite/MySQL)
- ✅ User authentication dan authorization
- ✅ CRUD operations lengkap
- ✅ E-commerce functionality (cart, checkout, orders)
- ✅ Admin panel dengan dashboard
- ✅ Responsive design untuk berbagai device
- ✅ Product variants dan specifications
- ✅ Review system dengan admin reply
- ✅ Image management dengan processing

## 📞 Support

Jika Anda menemukan masalah yang tidak tercakup di bagian troubleshooting:

1. Periksa Laravel logs: `storage/logs/laravel.log`
2. Verifikasi semua prasyarat sudah terinstall dengan benar
3. Pastikan semua migrations sudah berjalan dengan sukses
4. Periksa file permissions pada direktori `storage/` dan `bootstrap/cache/`

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

