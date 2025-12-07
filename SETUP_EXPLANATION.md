# Penjelasan File Setup: .bat, .md, dan .sh

## Perbedaan Utama

### 1. **`.bat` (Batch File) - Windows**

**Apa itu:**
- File script untuk Windows Command Prompt
- Ekstensi: `.bat` atau `.cmd`
- Dijalankan dengan double-click atau dari Command Prompt

**Cara Kerja:**
- Windows membaca file `.bat` baris per baris
- Menjalankan perintah-perintah yang ada di dalamnya secara berurutan
- Menggunakan syntax Windows batch scripting

**Cara Menggunakan:**
```cmd
# Double-click file setup.bat
# ATAU
# Buka Command Prompt, lalu:
setup.bat
```

**Contoh Syntax:**
```batch
@echo off
echo "Installing dependencies..."
composer install
php artisan migrate
```

**Kapan Digunakan:**
- Hanya untuk Windows
- Otomatisasi setup untuk user Windows
- Tidak bisa dijalankan di Linux/Mac

---

### 2. **`.sh` (Shell Script) - Linux/Mac**

**Apa itu:**
- File script untuk Unix/Linux/Mac terminal
- Ekstensi: `.sh`
- Dijalankan dengan bash shell

**Cara Kerja:**
- Linux/Mac membaca file `.sh` menggunakan bash interpreter
- Menjalankan perintah-perintah shell secara berurutan
- Menggunakan syntax bash scripting

**Cara Menggunakan:**
```bash
# Berikan permission execute terlebih dahulu:
chmod +x setup.sh

# Kemudian jalankan:
./setup.sh

# ATAU langsung:
bash setup.sh
```

**Contoh Syntax:**
```bash
#!/bin/bash
echo "Installing dependencies..."
composer install
php artisan migrate
```

**Kapan Digunakan:**
- Untuk Linux dan Mac
- Otomatisasi setup untuk user Unix-based
- Tidak bisa dijalankan langsung di Windows (kecuali dengan WSL atau Git Bash)

---

### 3. **`.md` (Markdown) - Dokumentasi**

**Apa itu:**
- File dokumentasi dalam format Markdown
- Ekstensi: `.md`
- Bukan script yang bisa dijalankan, hanya dokumentasi

**Cara Kerja:**
- File teks biasa yang menggunakan format Markdown
- Dibaca oleh manusia (bukan dieksekusi)
- Bisa ditampilkan dengan formatting di GitHub, VS Code, dll

**Cara Menggunakan:**
- Dibuka dengan text editor atau Markdown viewer
- Dibaca sebagai panduan/instruksi
- Tidak bisa dijalankan sebagai script

**Contoh Syntax:**
```markdown
# Judul
## Subjudul
- List item
**Bold text**
```

**Kapan Digunakan:**
- Dokumentasi proyek (README.md)
- Panduan instalasi
- Penjelasan fitur
- Instruksi manual

---

## Perbandingan

| Aspek | `.bat` | `.sh` | `.md` |
|-------|--------|-------|-------|
| **Platform** | Windows | Linux/Mac | Semua (dokumentasi) |
| **Dapat Dijalankan?** | ✅ Ya | ✅ Ya | ❌ Tidak (hanya dibaca) |
| **Tujuan** | Otomatisasi Windows | Otomatisasi Unix | Dokumentasi |
| **Syntax** | Windows Batch | Bash Shell | Markdown |
| **Cara Jalankan** | Double-click atau `setup.bat` | `./setup.sh` atau `bash setup.sh` | Buka dengan editor |

---

## Bagaimana Setup Script Bekerja?

### Setup.bat (Windows)

1. **Check Prerequisites**
   ```batch
   where php >nul 2>nul
   if %ERRORLEVEL% NEQ 0 (
       echo [ERROR] PHP is not installed
       exit /b 1
   )
   ```
   - Mengecek apakah PHP, Composer, Node.js sudah terinstall
   - Jika tidak ada, script berhenti dan menampilkan error

2. **Install Dependencies**
   ```batch
   call composer install --no-interaction
   call npm install
   ```
   - Menjalankan `composer install` dan `npm install`
   - `--no-interaction` = tidak perlu input dari user

3. **Setup Environment**
   ```batch
   if not exist .env (
       copy .env.example .env
       call php artisan key:generate
   )
   ```
   - Cek apakah file `.env` sudah ada
   - Jika belum, copy dari `.env.example` dan generate key

4. **Database Setup**
   ```batch
   type nul > database\database.sqlite
   call php artisan migrate --force
   call php artisan db:seed --force
   ```
   - Buat file database SQLite
   - Jalankan migrations dan seeding

5. **Build Assets**
   ```batch
   call npm run build
   ```
   - Build frontend assets untuk production

### Setup.sh (Linux/Mac)

1. **Check Prerequisites**
   ```bash
   if ! command -v php &> /dev/null; then
       echo "❌ PHP is not installed"
       exit 1
   fi
   ```
   - Sama seperti `.bat`, tapi menggunakan syntax bash
   - `command -v` = cek apakah command tersedia

2. **Install Dependencies**
   ```bash
   composer install --no-interaction
   npm install
   ```
   - Sama seperti Windows, tapi tanpa `call`

3. **Setup Environment**
   ```bash
   if [ ! -f .env ]; then
       cp .env.example .env
       php artisan key:generate
   fi
   ```
   - `[ ! -f .env ]` = cek apakah file tidak ada
   - `cp` = copy (bukan `copy` seperti Windows)

4. **Database Setup**
   ```bash
   touch database/database.sqlite
   php artisan migrate --force
   php artisan db:seed --force
   ```
   - `touch` = buat file (bukan `type nul >`)

5. **Build Assets**
   ```bash
   npm run build
   ```
   - Sama seperti Windows

---

## Keuntungan Menggunakan Setup Script

### ✅ Otomatisasi
- Tidak perlu mengetik perintah satu per satu
- Semua langkah dilakukan otomatis

### ✅ Error Handling
- Script mengecek prerequisites sebelum mulai
- Jika ada error, script berhenti dan memberitahu user

### ✅ Konsistensi
- Semua user melakukan setup dengan cara yang sama
- Mengurangi kemungkinan error karena typo atau langkah terlewat

### ✅ User-Friendly
- User hanya perlu double-click (Windows) atau jalankan satu command (Linux/Mac)
- Tidak perlu pengetahuan teknis mendalam

---

## Kapan Menggunakan Masing-Masing?

### Gunakan `.bat` jika:
- Target user menggunakan Windows
- Ingin setup otomatis untuk Windows
- User tidak familiar dengan command line

### Gunakan `.sh` jika:
- Target user menggunakan Linux atau Mac
- Ingin setup otomatis untuk Unix-based system
- User familiar dengan terminal

### Gunakan `.md` jika:
- Ingin memberikan dokumentasi lengkap
- User perlu memahami setiap langkah
- Ingin dokumentasi yang bisa dibaca di GitHub/editor

---

## Best Practice

1. **Sediakan Keduanya**: `.bat` untuk Windows dan `.sh` untuk Linux/Mac
2. **Dokumentasi Lengkap**: README.md dengan instruksi manual
3. **Error Messages Jelas**: Script harus memberikan pesan error yang jelas
4. **Check Prerequisites**: Selalu cek apakah tools yang diperlukan sudah terinstall
5. **Idempotent**: Script bisa dijalankan berulang kali tanpa masalah

---

## Contoh Penggunaan di Project Ini

**Windows User:**
```
1. Double-click setup.bat
2. Tunggu sampai selesai
3. Jalankan: php artisan serve
```

**Linux/Mac User:**
```
1. chmod +x setup.sh
2. ./setup.sh
3. php artisan serve
```

**Manual Setup (dari README.md):**
```
1. Baca README.md
2. Ikuti langkah-langkah manual
3. Copy-paste perintah satu per satu
```

---

**Kesimpulan:**
- `.bat` = Script Windows (otomatisasi)
- `.sh` = Script Linux/Mac (otomatisasi)
- `.md` = Dokumentasi (panduan manual)

Semuanya memiliki tujuan yang berbeda tapi saling melengkapi untuk memudahkan setup project!

