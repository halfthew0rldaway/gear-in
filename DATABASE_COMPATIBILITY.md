# Database Compatibility Guide

## 📊 Database yang Didukung

Project ini mendukung beberapa database dengan konfigurasi yang berbeda:

### 1. **SQLite (Default - Recommended untuk College Project)**

**✅ Tidak Terpengaruh Versi SQL**

SQLite adalah database file-based yang:
- **Tidak memerlukan server terpisah** - hanya file database
- **Tidak terpengaruh versi SQL** - karena self-contained
- **Portable** - bisa dipindah ke komputer lain dengan mudah
- **Zero configuration** - langsung bisa digunakan
- **Cocok untuk development dan testing**

**Requirements:**
- PHP extension: `pdo_sqlite` (biasanya sudah included)
- Tidak perlu install software tambahan

**Kompatibilitas:**
- ✅ Windows, Linux, macOS
- ✅ Semua versi PHP 8.2+
- ✅ Tidak perlu konfigurasi server

---

### 2. **MySQL/MariaDB (Opsional)**

**⚠️ Versi SQL Mempengaruhi Kompatibilitas**

Jika Anda menggunakan MySQL/MariaDB, versi database **BISA MEMPENGARUHI** project:

#### **Versi Minimum yang Didukung:**
- **MySQL 5.7+** atau **MySQL 8.0+** (Recommended)
- **MariaDB 10.3+**

#### **Fitur yang Digunakan:**
- `JSON` column type (MySQL 5.7+ / MariaDB 10.2+)
- `utf8mb4` charset (untuk emoji support)
- Foreign key constraints
- Transactions

#### **Jika Versi MySQL Terlalu Lama (< 5.7):**
- ❌ `JSON` column tidak didukung
- ❌ Beberapa migration mungkin gagal
- ⚠️ Perlu downgrade beberapa fitur

#### **Rekomendasi untuk MySQL:**
- **MySQL 8.0+** (Latest stable)
- Atau **MariaDB 10.6+** (Alternative)

---

### 3. **PostgreSQL (Opsional)**

**⚠️ Versi SQL Mempengaruhi Kompatibilitas**

Jika menggunakan PostgreSQL:
- **Minimum: PostgreSQL 10+**
- **Recommended: PostgreSQL 13+**

---

## 🎯 Rekomendasi untuk College Project

### **Gunakan SQLite (Default)**

**Alasan:**
1. ✅ **Tidak perlu install database server** - lebih mudah untuk lecturer/test
2. ✅ **Tidak terpengaruh versi SQL** - kompatibel di semua sistem
3. ✅ **Portable** - bisa dikirim sebagai file
4. ✅ **Zero configuration** - langsung jalan
5. ✅ **Cukup untuk tugas** - fitur lengkap untuk e-commerce

**Cara Setup:**
```bash
# File database akan dibuat otomatis saat migrate
php artisan migrate
```

---

## 🔍 Cara Cek Versi Database (Jika Menggunakan MySQL)

### Cek Versi MySQL:
```bash
mysql --version
# atau
mysql -u root -p -e "SELECT VERSION();"
```

### Cek Versi MariaDB:
```bash
mariadb --version
# atau
mariadb -u root -p -e "SELECT VERSION();"
```

---

## ⚠️ Troubleshooting

### Masalah: "JSON column type not supported"

**Penyebab:** MySQL versi < 5.7

**Solusi:**
1. Upgrade MySQL ke versi 5.7+ atau 8.0+
2. Atau gunakan SQLite (default)

### Masalah: "Syntax error near 'JSON'"

**Penyebab:** Database tidak support JSON type

**Solusi:**
- Gunakan SQLite (sudah support JSON)
- Atau upgrade MySQL/MariaDB

### Masalah: "Foreign key constraint fails"

**Penyebab:** Database engine tidak support (misal: MyISAM di MySQL lama)

**Solusi:**
- Pastikan menggunakan InnoDB engine (default di MySQL 5.5+)
- Atau gunakan SQLite

---

## 📝 Kesimpulan

### **Untuk College Project:**
✅ **Gunakan SQLite** - Tidak terpengaruh versi SQL, mudah setup, portable

### **Untuk Production (Future):**
⚠️ **MySQL 8.0+** atau **PostgreSQL 13+** - Perlu perhatikan versi untuk kompatibilitas

---

## 🔗 Referensi

- [Laravel Database Documentation](https://laravel.com/docs/12.x/database)
- [SQLite Documentation](https://www.sqlite.org/docs.html)
- [MySQL Version Compatibility](https://dev.mysql.com/doc/refman/8.0/en/)
- [MariaDB Version Compatibility](https://mariadb.com/kb/en/mariadb-versions/)

