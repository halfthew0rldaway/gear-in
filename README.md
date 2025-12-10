# Gear-In E-Commerce Platform

**Tugas Besar Mata Kuliah Pemrograman Web Lanjut**

Platform e-commerce modern berbasis web yang dibangun menggunakan Laravel 12, dilengkapi dengan katalog produk, keranjang belanja, manajemen pesanan, sistem review, wishlist, dashboard administrasi, sistem discount/voucher, dan promo widget dengan animasi yang menarik.

> **💡 Catatan untuk Tester/Evaluator:** Aplikasi ini **mendukung MySQL dan SQLite**. Untuk kemudahan testing dan monitoring database, **direkomendasikan menggunakan MySQL dengan HeidiSQL**. Namun, Anda bebas menggunakan database sesuai preferensi Anda.

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
   - Ekstensi yang diperlukan: `pdo`, `pdo_mysql`, `pdo_sqlite`, `mbstring`, `xml`, `ctype`, `json`, `openssl`, `tokenizer`, `fileinfo`
   
2. **Database (Pilih salah satu):**
   
   **Opsi A - MySQL Server** (Recommended untuk Testing)
   - Install MySQL Server (XAMPP, WAMP, atau standalone MySQL)
   - Verifikasi: `mysql --version` atau melalui phpMyAdmin
   - **HeidiSQL** (Recommended untuk Database Management)
     - Unduh: [HeidiSQL Downloads](https://www.heidisql.com/download.php)
     - Tool untuk mengelola database MySQL dengan GUI yang user-friendly
     - Cocok untuk evaluator yang ingin melihat dan query database dengan mudah
   
   **Opsi B - SQLite** (Simple & Quick)
   - Tidak perlu install database server
   - File-based database, langsung bisa digunakan
   - Cocok untuk quick testing tanpa setup tambahan

2. **Composer** (PHP Dependency Manager)
   - Verifikasi versi: `composer --version`
   - Unduh: [Composer Downloads](https://getcomposer.org/download/)

4. **Node.js 18.x atau lebih tinggi** (untuk frontend assets)
   - Verifikasi versi: `node -v`
   - Unduh: [Node.js Downloads](https://nodejs.org/)

5. **npm** (termasuk dalam instalasi Node.js)
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
   
   Aplikasi ini mendukung **MySQL** dan **SQLite**. Anda bebas memilih sesuai kebutuhan.
   
   **💡 Recommended: MySQL dengan HeidiSQL (untuk kemudahan monitoring dan testing)**
   
   **Setup MySQL dengan HeidiSQL:**
   
   1. Install MySQL Server (XAMPP, WAMP, atau standalone MySQL)
   2. Install HeidiSQL: [Download HeidiSQL](https://www.heidisql.com/download.php)
   3. Buka HeidiSQL dan buat koneksi baru:
      - Host: `127.0.0.1` atau `localhost`
      - Port: `3306`
      - User: `root`
      - Password: (sesuaikan dengan MySQL Anda)
   4. Buat database baru dengan nama `gearin`:
      ```sql
      CREATE DATABASE gearin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
      ```
   5. Update file `.env` dengan kredensial MySQL Anda:
      ```
      DB_CONNECTION=mysql
      DB_HOST=127.0.0.1
      DB_PORT=3306
      DB_DATABASE=gearin
      DB_USERNAME=root
      DB_PASSWORD=your_password
      ```
   6. Jalankan migrations: `php artisan migrate`
   7. Jalankan seeding: `php artisan db:seed`
   
   **Alternatif: SQLite (Lebih Simple untuk Quick Testing)**
   
   Jika Anda ingin menggunakan SQLite (lebih simple, tidak perlu setup MySQL), ubah konfigurasi di `.env`:

    DB_CONNECTION=sqlite
    DB_DATABASE=C:\absolute\path\to\database\database.sqlite
    SESSION_DRIVER=file
   
   **Catatan untuk SQLite:**
   - Pastikan path di `DB_DATABASE` menggunakan absolute path (full path)
   - File database akan dibuat otomatis pada langkah berikutnya
   - SQLite cocok untuk quick testing tanpa perlu setup database server
   

## 🗄️ Konfigurasi Database

### Langkah 1: Setup Database

**Opsi A: MySQL dengan HeidiSQL (Recommended untuk Testing)**

**Menggunakan HeidiSQL:**

1. **Buka HeidiSQL** dan buat koneksi baru ke MySQL server Anda
2. **Buat database baru:**
   - Klik kanan pada koneksi > Create new > Database
   - Nama database: `gearin`
   - Collation: `utf8mb4_unicode_ci`
   - Klik OK

3. **Atau gunakan SQL command di HeidiSQL:**
   ```sql
   CREATE DATABASE gearin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

4. **Verifikasi database sudah dibuat:**
   - Database `gearin` harus muncul di list database di HeidiSQL

5. **Update file `.env`** dengan konfigurasi MySQL (lihat langkah 3 di bagian Instalasi)

**Keuntungan menggunakan MySQL/HeidiSQL:**
- ✅ Mudah monitoring data via GUI
- ✅ Bisa melihat struktur tabel dengan jelas
- ✅ Bisa query langsung untuk testing
- ✅ Cocok untuk evaluator yang ingin melihat database structure

**Opsi B: SQLite (Simple & Quick)**

Jika menggunakan SQLite, buat file database:

**Windows:**

    type nul > database\database.sqlite


**Linux/Mac:**

    touch database/database.sqlite

**Keuntungan menggunakan SQLite:**
- ✅ Tidak perlu setup database server
- ✅ File-based, mudah untuk backup
- ✅ Cocok untuk quick testing
- ✅ Simple dan langsung bisa digunakan

### Langkah 2: Menjalankan Migrations

Jalankan perintah berikut untuk membuat semua tabel database yang diperlukan:

    php artisan migrate


Output yang diharapkan:

INFO  Running migrations.

  2025_11_17_063640_create_categories_table ..................... DONE
  2025_11_17_063644_create_products_table ....................... DONE
  2025_11_17_063647_create_cart_items_table ..................... DONE
  ...


**💡 Tips untuk Fresh Install (Device Baru):**

Jika Anda melakukan setup di device baru atau mengalami masalah migration, gunakan perintah berikut untuk memastikan database bersih:

    php artisan migrate:fresh

Perintah ini akan:
- Menghapus semua tabel yang ada
- Membuat ulang semua tabel dari awal
- Menjalankan semua migrations dengan urutan yang benar

**⚠️ Peringatan:** Perintah `migrate:fresh` akan **menghapus semua data** yang ada di database!


### Langkah 3: Seeding Database

Jalankan perintah berikut untuk mengisi database dengan data sample:

    php artisan db:seed


Output yang diharapkan:

INFO  Seeding database.

Creating default users...
✅ Admin user created: admin@gear-in.dev
✅ Admin 2 user created: admin2@gear-in.dev
✅ Customer user created: customer@gear-in.dev
Running seeders...
  Database\Seeders\CategorySeeder ................................. DONE
  Database\Seeders\ProductSeeder .................................. DONE
✅ All seeders completed successfully!


**💡 Tips untuk Fresh Install (Device Baru):**

Jika Anda ingin melakukan migration dan seeding sekaligus di device baru:

    php artisan migrate:fresh --seed

Perintah ini akan:
- Menghapus semua tabel yang ada
- Membuat ulang semua tabel
- Menjalankan semua migrations
- Menjalankan semua seeders secara otomatis
- Membuat user default (admin dan customer)
- Membuat kategori, produk, orders, dan reviews sample


**💡 Alternatif: Jika ada error saat seeding**

Jika terjadi error saat seeding, Anda bisa menjalankan seeders satu per satu:

    php artisan db:seed --class=CategorySeeder
    php artisan db:seed --class=ProductSeeder
    php artisan db:seed --class=OrderSeeder
    php artisan db:seed --class=ReviewSeeder


**Data yang akan di-seed:**
- Kategori produk
- Sample produk dengan spesifikasi detail
- Akun admin dan customer default (lihat [Akun Default](#akun-default))

### Langkah 4: Verifikasi Setup Database

**Jika menggunakan MySQL/HeidiSQL:**
1. Buka database `gearin` di HeidiSQL
2. Periksa tabel-tabel yang sudah dibuat:
   - `users` - Seharusnya ada 3 user (2 admin, 1 customer)
   - `products` - Seharusnya ada beberapa produk sample
   - `categories` - Seharusnya ada beberapa kategori
   - `vouchers` - Tabel untuk voucher/promo codes
   - `orders` - Tabel untuk pesanan
   - Dan tabel-tabel lainnya
3. Anda bisa langsung query di HeidiSQL untuk melihat data

**Jika menggunakan SQLite:**
- Gunakan Laravel Tinker atau SQLite browser untuk melihat data

**Menggunakan Laravel Tinker (Universal):**

    php artisan tinker


Kemudian di console tinker:

    DB::table('users')->count();  // Seharusnya mengembalikan 3
    DB::table('products')->count();  // Seharusnya mengembalikan beberapa produk
    DB::table('vouchers')->count();  // Seharusnya 0 (belum ada voucher)
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
- ✅ **Rating Display** di katalog produk (bintang kuning + rating angka)
- ✅ **Quick Add to Cart** langsung dari katalog tanpa masuk detail produk
- ✅ **Variant Selection Dropdown** di katalog untuk produk bervarian
- ✅ **Product Card Animations** dengan fade-in dan hover effects
- ✅ **Smooth Animations** untuk semua interaksi (ringan dan performant)
- ✅ **Sistem Discount/Voucher** dengan validasi lengkap
- ✅ **Product Discount** dengan persentase dan periode aktif
- ✅ **Voucher Codes** dengan berbagai tipe (percentage/fixed)
- ✅ **Floating Promo Widget** dengan modal pop-up dan minimized button
- ✅ **Promo Modal** menampilkan produk dengan discount aktif
- ✅ **Custom Alert & Confirm Dialogs** menggantikan browser alerts
- ✅ **UI/UX Improvements** dengan warna merah untuk discount
- ✅ **Performance Optimizations** dengan Vite build optimizations

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
- ✅ **Manajemen Voucher/Promo Codes** dengan CRUD lengkap
- ✅ **Validasi Voucher** dengan minimum purchase, usage limit, expiry date
- ✅ **Product Discount Management** dari admin panel
- ✅ **Discount Display** di semua halaman (catalog, cart, checkout, receipt)
- ✅ **Fancy Modal Animations** untuk promo widget dengan GPU acceleration

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
- ✅ **CSS Animations** yang ringan dan GPU-accelerated
- ✅ **Stagger Animations** untuk product cards
- ✅ **Micro-interactions** untuk button dan links
- ✅ **Performance Optimized** animations (menggunakan transform dan opacity)
- ✅ **Discount System** dengan product-level dan voucher-level discounts
- ✅ **Voucher Usage Tracking** untuk monitoring penggunaan voucher
- ✅ **Order Discount Integration** dengan perhitungan otomatis
- ✅ **Receipt dengan Discount Info** menampilkan semua discount yang diterapkan
- ✅ **Layout Consistency** dengan standardized spacing dan symmetry
- ✅ **Vite Build Optimizations** dengan code splitting dan minification

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

## 💰 Sistem Discount dan Voucher

Aplikasi ini dilengkapi dengan sistem discount dan voucher yang lengkap:

### Product Discount
- **Persentase Discount:** Setiap produk dapat memiliki discount persentase
- **Periode Aktif:** Discount dapat diatur dengan tanggal mulai dan berakhir
- **Automatic Calculation:** Harga discount dihitung otomatis di semua halaman
- **Visual Indicator:** Badge discount merah dengan animasi pulse
- **Price Display:** Harga discount ditampilkan dengan warna merah, harga asli dengan strikethrough

### Voucher System
- **Tipe Voucher:**
  - Percentage: Discount berdasarkan persentase (dengan max discount limit)
  - Fixed: Discount dengan nominal tetap
- **Validasi Lengkap:**
  - Minimum purchase amount
  - Usage limit (total dan per user)
  - Expiry date
  - Active/inactive status
- **Usage Tracking:** Setiap penggunaan voucher dicatat untuk monitoring
- **Admin Management:** CRUD lengkap untuk voucher dari admin panel

### Promo Widget
- **Floating Modal:** Modal pop-up di tengah layar saat pertama login
- **Product List:** Menampilkan produk dengan discount aktif (maksimal 3 produk)
- **Fancy Animations:** 
  - Modal slide-up dengan backdrop blur
  - Stagger animation untuk item produk
  - Pulse glow untuk discount badge
  - GPU-accelerated untuk performa optimal
- **Minimized Widget:** Floating button di samping chat widget setelah modal ditutup
- **Auto-hide:** Widget tidak muncul jika tidak ada produk discount

### Discount Display
Discount ditampilkan dengan konsisten di semua halaman:
- ✅ **Catalog:** Badge discount dan harga merah
- ✅ **Product Detail:** Badge dan harga discount
- ✅ **Cart:** Badge dan harga discount per item
- ✅ **Checkout:** Ringkasan discount produk dan voucher
- ✅ **Payment:** Detail discount di ringkasan pesanan
- ✅ **Order Detail:** Ringkasan discount lengkap
- ✅ **Receipt:** Discount tercantum di receipt

## 🎨 Animasi dan Interaksi

Aplikasi ini menggunakan kombinasi animasi CSS dan JavaScript yang ringan dan dioptimalkan untuk performa:

### Animasi CSS yang Tersedia

- **Product Card Fade-In:** Product cards muncul dengan animasi fade-in bertahap (stagger animation)
- **Image Zoom on Hover:** Gambar produk sedikit zoom saat hover untuk memberikan depth
- **Button Ripple Effect:** Feedback visual ringan saat hover/klik tombol
- **Rating Stars Hover:** Rating stars sedikit membesar saat hover
- **Link Underline Animation:** Underline muncul secara smooth saat hover
- **Smooth Scroll:** Scroll behavior yang halus untuk navigasi
- **Success Notification Slide-In:** Notifikasi muncul dari kanan dengan animasi smooth
- **Focus Ring Animation:** Ring animation untuk accessibility pada form inputs

### Animasi JavaScript yang Tersedia

- **Scroll Reveal Animation:** Elemen muncul dengan fade-in saat masuk viewport menggunakan Intersection Observer
- **Smooth Number Counter:** Animasi counter untuk angka statistik (dashboard stats, dll)
- **Table Row Highlight:** Highlight baris tabel saat hover dengan transisi halus
- **Loading Skeleton:** Skeleton loader saat data dimuat
- **Toast Notification:** Notifikasi toast yang muncul dari kanan dengan slide animation
- **Image Lazy Loading:** Fade-in gambar saat dimuat untuk meningkatkan performa
- **Smooth Scroll to Top:** Tombol scroll to top dengan fade-in/out otomatis
- **Form Validation Shake:** Animasi shake untuk field yang invalid
- **Progress Bar:** Progress bar untuk multi-step forms dengan animasi smooth
- **Stagger Animation:** Animasi bertahap untuk list items (orders, products, reviews, dll)

### Optimasi Performa

Semua animasi menggunakan teknik berikut untuk memastikan performa optimal:

- ✅ **GPU-Accelerated:** Menggunakan `transform` dan `opacity` (tidak trigger reflow)
- ✅ **Intersection Observer:** Untuk scroll reveal dan lazy loading (lebih efisien dari scroll event)
- ✅ **RequestAnimationFrame:** Untuk animasi counter yang smooth
- ✅ **Lightweight:** Durasi animasi pendek (200-400ms) untuk responsif
- ✅ **Reduced Motion Support:** Menghormati preferensi user untuk reduced motion
- ✅ **No Layout Shifts:** Animasi tidak menyebabkan perubahan layout yang tidak diinginkan
- ✅ **Passive Event Listeners:** Untuk scroll events yang lebih performant
- ✅ **Promo Modal Animations:** Fancy slide-up dengan backdrop blur dan stagger effects
- ✅ **Discount Badge Pulse:** Animasi pulse glow untuk menarik perhatian
- ✅ **Vite Build Optimizations:** Code splitting, minification, terser compression

### Teknologi Animasi

- **Tailwind CSS Transitions:** Untuk transisi dasar
- **Custom CSS Keyframes:** Untuk animasi kompleks (shake, loading skeleton, dll)
- **CSS Variables:** Untuk delay animation yang dinamis
- **Transform & Opacity:** Untuk animasi yang smooth dan performant
- **JavaScript Intersection Observer API:** Untuk scroll reveal dan lazy loading
- **Vanilla JavaScript:** Tidak menggunakan library eksternal untuk animasi (ringan dan cepat)

### Cara Menggunakan Animasi

#### Scroll Reveal
Tambahkan class `scroll-reveal` ke elemen yang ingin di-animate:
```html
<div class="scroll-reveal">Content akan muncul saat scroll</div>
```

#### Counter Animation
Tambahkan attribute `data-counter-target` dengan nilai target:
```html
<p data-counter-target="150">0</p>
```

#### Stagger Animation
Tambahkan attribute `data-stagger` dan `data-stagger-selector`:
```html
<div data-stagger="100" data-stagger-selector="> div">
    <div>Item 1</div>
    <div>Item 2</div>
</div>
```

#### Toast Notification
Panggil fungsi `showToast()` dari JavaScript:
```javascript
showToast('Pesan sukses!', 'success');
showToast('Terjadi error!', 'error');
```

#### Shake Animation
Panggil fungsi `shakeElement()` untuk field yang invalid:
```javascript
shakeElement(document.getElementById('field'));
```

## 🔍 Troubleshooting

### Masalah: "Class not found" atau "Autoload error"

**Solusi:**

    composer dump-autoload


### Masalah: "Database file does not exist" (SQLite)

**Solusi:**
1. Pastikan file `database/database.sqlite` sudah ada
2. Periksa file permissions (harus readable/writable)
3. Verifikasi path di `.env` menggunakan absolute path

**Catatan:** Jika menggunakan SQLite dan mengalami masalah, pertimbangkan untuk menggunakan MySQL/HeidiSQL yang lebih mudah dikelola.

### Masalah: "Access denied for user" (MySQL)

**Solusi:**
1. Verifikasi kredensial MySQL di file `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=gearin
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```
2. Pastikan database `gearin` sudah dibuat di MySQL/HeidiSQL
3. Pastikan user MySQL memiliki akses ke database tersebut
4. Test koneksi di HeidiSQL terlebih dahulu sebelum menjalankan migrations
5. **Alternatif:** Jika masalah persist, gunakan SQLite untuk quick testing

### Masalah: "Migration error" atau "Table already exists" atau "Column already exists"

**Penyebab:** Biasanya terjadi karena:
- Migration sudah pernah dijalankan sebelumnya
- Ada konflik kolom yang sudah ada
- Database tidak dalam keadaan fresh

**Solusi:**

**Opsi 1: Fresh Migration (Recommended untuk Fresh Install)**
```bash
php artisan migrate:fresh --seed
```
Perintah ini akan menghapus semua tabel dan membuat ulang dari awal, lalu menjalankan seeders.

**⚠️ Peringatan:** Perintah ini akan **menghapus semua data** yang ada di database!

**Opsi 2: Reset Migration (Jika ingin reset tapi tidak ingin re-seed)**
```bash
php artisan migrate:reset
php artisan migrate
```

**Opsi 3: Rollback dan Migrate Ulang**
```bash
php artisan migrate:rollback --step=10
php artisan migrate
```

**Opsi 4: Untuk Device Baru/Testing**
Jika Anda di device baru atau sedang testing, gunakan:
```bash
php artisan migrate:fresh --seed
```
Ini adalah cara paling aman untuk memastikan semua migrations berjalan dengan benar.

### Masalah: "SQLSTATE[42S21]: Column already exists" - Khusus untuk Role Column

**Penyebab:** Ada duplikasi kolom `role` di tabel `users`.

**Solusi:**
Masalah ini sudah diperbaiki dengan menghapus migration duplicate. Jika masih terjadi error, jalankan:
```bash
php artisan migrate:fresh --seed
```

### Masalah: "SQLSTATE[HY000]: General error" atau "Migration failed"

**Solusi:**
1. Pastikan database connection di `.env` sudah benar
2. Pastikan database sudah dibuat (untuk MySQL)
3. Pastikan file database ada (untuk SQLite): `database/database.sqlite`
4. Coba jalankan fresh migration:
   ```bash
   php artisan migrate:fresh --seed
   ```
5. Jika masih error, cek log: `storage/logs/laravel.log`

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


### Masalah: "Permission denied" pada database file (SQLite)

**Solusi (Linux/Mac):**

    chmod 664 database/database.sqlite
    chmod 775 database


**Solusi (Windows):**
- Klik kanan pada file `database.sqlite`
- Properties > Security
- Pastikan user Anda memiliki Read & Write permissions

**Catatan:** Jika menggunakan SQLite dan mengalami masalah permission, pertimbangkan untuk menggunakan MySQL/HeidiSQL yang lebih mudah dikelola.

### Masalah: Port 8000 sudah digunakan

**Solusi:**
Gunakan port yang berbeda:

    php artisan serve --port=8001


### Masalah: "Vite manifest not found"

**Solusi:**

    npm run build


### Masalah: Tidak bisa login dengan akun default

**Penyebab:** User default belum dibuat atau ada masalah saat seeding.

**Solusi:**

**Opsi 1: Re-seed database (tanpa menghapus data)**
```bash
php artisan db:seed
```

**Opsi 2: Fresh migrate dengan seed (jika opsi 1 tidak bekerja)**
```bash
php artisan migrate:fresh --seed
```

**Opsi 3: Buat user secara manual via Tinker**
```bash
php artisan tinker
```

Kemudian di console tinker:
```php
\App\Models\User::updateOrCreate(
    ['email' => 'admin@gear-in.dev'],
    [
        'name' => 'Gear-In Admin',
        'role' => 'admin',
        'password' => bcrypt('password'),
    ]
);
exit
```


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
   
   (Linux/Mac: `cp .env.example .env`)

3. **Setup database:**
   
   **Opsi A - MySQL dengan HeidiSQL (Recommended):**
   - Buat database `gearin` di HeidiSQL
   - Update `.env` dengan kredensial MySQL
   - **Jalankan migrations dan seeding (fresh):**
     ```bash
     php artisan migrate:fresh --seed
     ```
   
   **Opsi B - SQLite (Simple & Recommended untuk Testing):**
   
   **Windows:**
   ```bash
   type nul > database\database.sqlite
   ```
   
   **Linux/Mac:**
   ```bash
   touch database/database.sqlite
   ```
   
   Atau file akan dibuat otomatis saat migrate.
   
   **Jalankan migrations dan seeding (fresh - Recommended untuk device baru):**
   ```bash
   php artisan migrate:fresh --seed
   ```
   
   **💡 Catatan:** Untuk device baru atau testing, sangat disarankan menggunakan `migrate:fresh --seed` untuk memastikan semua migrations berjalan dengan benar tanpa konflik.

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
- ✅ Database integration (MySQL dan SQLite - bebas pilih sesuai kebutuhan)
- ✅ User authentication dan authorization
- ✅ CRUD operations lengkap
- ✅ E-commerce functionality (cart, checkout, orders)
- ✅ Admin panel dengan dashboard
- ✅ Responsive design untuk berbagai device
- ✅ Product variants dan specifications
- ✅ Review system dengan admin reply
- ✅ Image management dengan processing
- ✅ Discount/Voucher system dengan validasi lengkap
- ✅ Product discount dengan periode aktif
- ✅ Voucher codes dengan berbagai tipe dan limit
- ✅ Promo widget dengan fancy animations
- ✅ Custom UI components (alerts, confirms, toasts)
- ✅ Performance optimizations (Vite build, code splitting)
- ✅ Layout consistency dan symmetry

## 🧪 Panduan untuk Tester/Evaluator

### Database Setup

**💡 Recommended: MySQL dengan HeidiSQL**

Menggunakan MySQL dengan HeidiSQL direkomendasikan karena:
- ✅ Mudah monitoring dan melihat data via GUI
- ✅ Bisa query langsung untuk testing
- ✅ Bisa melihat struktur tabel dengan jelas
- ✅ Cocok untuk evaluator yang ingin melihat database structure

**Setup MySQL dengan HeidiSQL:**

1. **Install MySQL Server:**
   - XAMPP, WAMP, atau standalone MySQL
   - Pastikan MySQL service berjalan

2. **Install HeidiSQL:**
   - Download dari: https://www.heidisql.com/download.php
   - Install dan buka aplikasi

3. **Setup Database:**
   - Buat koneksi baru di HeidiSQL
   - Host: `127.0.0.1`, Port: `3306`
   - User: `root`, Password: (sesuaikan)
   - Buat database `gearin` dengan collation `utf8mb4_unicode_ci`

4. **Konfigurasi `.env`:**
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=gearin
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

5. **Run Migrations:**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Verifikasi di HeidiSQL:**
   - Buka database `gearin`
   - Periksa semua tabel sudah dibuat
   - Periksa data sample sudah ter-seed

**Alternatif: SQLite (Simple & Quick)**

Jika Anda lebih suka menggunakan SQLite (tidak perlu setup database server):
- Ikuti langkah setup SQLite di bagian [Konfigurasi Database](#-konfigurasi-database)
- SQLite cocok untuk quick testing tanpa perlu setup MySQL

### Database Tables untuk Testing

Berikut adalah tabel-tabel penting yang ada di database:

**User & Authentication:**
- `users` - User accounts (admin dan customer)
- `password_reset_tokens` - Password reset tokens

**Products & Catalog:**
- `categories` - Kategori produk
- `products` - Produk dengan discount fields
- `product_variants` - Varian produk
- `product_images` - Multiple images per produk

**Shopping:**
- `cart_items` - Item di keranjang
- `wishlists` - Wishlist customer

**Orders:**
- `orders` - Pesanan dengan discount dan voucher info
- `order_items` - Item dalam pesanan
- `order_status_histories` - Timeline status pesanan

**Discount & Voucher:**
- `vouchers` - Voucher/promo codes
- `voucher_usages` - Tracking penggunaan voucher

**Reviews:**
- `reviews` - Review dan rating produk

**Chat:**
- `conversations` - Percakapan customer-admin
- `messages` - Pesan dalam percakapan

**System:**
- `activity_logs` - Activity logging
- `cache`, `jobs`, `sessions` - Laravel system tables

### Testing Checklist

- [ ] Database berhasil dibuat dan terkoneksi (MySQL atau SQLite)
- [ ] Semua migrations berhasil dijalankan
- [ ] Data sample ter-seed dengan benar
- [ ] Login sebagai admin dan customer berhasil
- [ ] Fitur discount produk berfungsi
- [ ] Sistem voucher dapat dibuat dan digunakan
- [ ] Promo widget muncul saat ada produk discount
- [ ] Checkout dengan voucher berhasil
- [ ] Receipt menampilkan discount dengan benar
- [ ] Semua CRUD operations berfungsi
- [ ] Database dapat diakses (HeidiSQL untuk MySQL, atau SQLite browser untuk SQLite)

## 📞 Support

Jika Anda menemukan masalah yang tidak tercakup di bagian troubleshooting:

1. Periksa Laravel logs: `storage/logs/laravel.log`
2. Verifikasi semua prasyarat sudah terinstall dengan benar
3. Pastikan semua migrations sudah berjalan dengan sukses
4. Periksa file permissions pada direktori `storage/` dan `bootstrap/cache/`
5. **Untuk masalah database MySQL:** Verifikasi koneksi di HeidiSQL terlebih dahulu, atau pertimbangkan menggunakan SQLite untuk quick testing
6. **Untuk masalah discount/voucher:** Periksa data di tabel `vouchers` dan field `discount_percentage` di tabel `products` (bisa via HeidiSQL atau SQLite browser)

## 📝 Changelog - Update Terbaru

### Update Terbaru - Fix Migration & Seeding Issues

#### 🔧 Perbaikan Migration dan Seeding

1. **Fix Duplicate Role Column Migration:**
   - ❌ Dihapus migration duplicate `2025_12_07_024200_add_role_to_users_table.php`
   - ✅ Kolom `role` sudah ada di migration awal `create_users_table.php`
   - Masalah ini menyebabkan error "Column already exists" saat migrate di device baru

2. **Improve Seeder Robustness:**
   - ✅ DatabaseSeeder sekarang membuat users **SEBELUM** menjalankan seeders lain
   - ✅ OrderSeeder dan ReviewSeeder sekarang memiliki error handling yang lebih baik
   - ✅ Seeders memberikan feedback yang jelas saat berjalan
   - ✅ Seeders dapat handle kasus data belum ada dengan lebih baik

3. **Device-Friendly Migration:**
   - ✅ Semua migrations sekarang dapat berjalan di fresh install tanpa kendala
   - ✅ Tidak ada lagi konflik kolom atau dependency issues
   - ✅ Recommended menggunakan `php artisan migrate:fresh --seed` untuk device baru

4. **Update Documentation:**
   - ✅ README diperbarui dengan instruksi migration yang lebih jelas
   - ✅ Troubleshooting section diperluas dengan solusi untuk masalah umum
   - ✅ Instruksi khusus untuk fresh install di device baru
   - ✅ Tips dan alternatif solusi untuk berbagai skenario

#### 📋 Rekomendasi untuk Fresh Install

Untuk device baru atau testing, gunakan perintah berikut:
```bash
php artisan migrate:fresh --seed
```

Ini akan:
- ✅ Menghapus semua tabel yang ada
- ✅ Membuat ulang semua tabel dengan urutan yang benar
- ✅ Menjalankan semua migrations tanpa konflik
- ✅ Menjalankan semua seeders dengan urutan yang benar
- ✅ Membuat user default (admin dan customer)
- ✅ Membuat data sample (categories, products, orders, reviews)

### Update Hari Ini (Latest)

#### ✨ Fitur Baru

1. **Sistem Discount/Voucher Lengkap:**
   - Product-level discount dengan persentase dan periode aktif
   - Voucher system dengan tipe percentage dan fixed
   - Validasi lengkap (minimum purchase, usage limit, expiry date)
   - Admin CRUD untuk voucher management
   - Voucher usage tracking

2. **Floating Promo Widget:**
   - Modal pop-up di tengah layar saat pertama login customer
   - Menampilkan produk dengan discount aktif (maksimal 3 produk)
   - Fancy animations dengan GPU acceleration
   - Minimized floating button setelah modal ditutup
   - Auto-hide jika tidak ada produk discount

3. **UI/UX Improvements:**
   - Custom alert & confirm dialogs menggantikan browser alerts
   - Discount display dengan warna merah konsisten di semua halaman
   - Layout standardization dengan utility classes
   - Symmetric dan proper alignment di semua panel

4. **Performance Optimizations:**
   - Vite build optimizations (code splitting, minification)
   - GPU-accelerated animations
   - Optimized CSS dan JavaScript
   - Reduced bundle size

#### 🎨 Animasi Baru

- **Promo Modal Animations:**
  - Slide-up dengan backdrop blur
  - Stagger animation untuk item produk
  - Pulse glow untuk discount badge
  - Smooth fade in/out transitions

#### 🗄️ Database Updates

**Migrations Baru:**
- `create_vouchers_table` - Tabel untuk voucher/promo codes
- `create_voucher_usages_table` - Tracking penggunaan voucher
- `add_discount_to_products_table` - Discount fields untuk produk
- `add_discount_to_orders_table` - Discount fields untuk orders

**Model Baru:**
- `Voucher` - Model untuk voucher management
- `VoucherUsage` - Model untuk tracking penggunaan

**Service Baru:**
- `VoucherService` - Service untuk validasi dan aplikasi voucher

#### 🔧 Technical Improvements

- Updated `CartService` untuk menghitung discount produk
- Updated `CheckoutController` untuk integrasi voucher
- Updated `Order` model dengan relasi voucher
- Updated semua views untuk menampilkan discount dengan konsisten
- Performance optimizations di Vite config

### Database Schema Updates

**Tabel `products`:**
- `discount_percentage` (decimal) - Persentase discount
- `discount_starts_at` (timestamp) - Tanggal mulai discount
- `discount_expires_at` (timestamp) - Tanggal berakhir discount

**Tabel `orders`:**
- `discount` (decimal) - Total discount dari voucher
- `voucher_id` (foreign key) - Relasi ke voucher yang digunakan

**Tabel `vouchers`:**
- `code` (string, unique) - Kode voucher
- `name` (string) - Nama voucher
- `type` (enum: percentage/fixed) - Tipe discount
- `value` (decimal) - Nilai discount
- `min_purchase` (decimal) - Minimum pembelian
- `max_discount` (decimal) - Max discount untuk percentage
- `usage_limit` (integer) - Batas penggunaan total
- `usage_count` (integer) - Jumlah sudah digunakan
- `user_limit` (integer) - Batas per user
- `starts_at`, `expires_at` (timestamp) - Periode aktif
- `is_active` (boolean) - Status aktif

**Tabel `voucher_usages`:**
- `voucher_id` (foreign key)
- `user_id` (foreign key)
- `order_id` (foreign key, nullable)
- `discount_amount` (decimal) - Jumlah discount yang diterapkan

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

