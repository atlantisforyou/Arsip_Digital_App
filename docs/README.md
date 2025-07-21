📺 **Link Video Demo Aplikasi:** https://youtu.be/-KPYYDyNwr0  
🌐 **Aplikasi Online:** https://digitalelina.my.id/login.php 

# Arsip Digital - Dokumentasi Aplikasi

## Deskripsi
Arsip Digital adalah aplikasi web untuk mengelola dan mengarsipkan dokumen kegiatan organisasi. Aplikasi ini memungkinkan pengguna untuk menyimpan, mengelola, dan mengakses dokumen-dokumen kegiatan secara digital dengan sistem role-based access (Admin dan User).

---

## a) Cara Instalasi Aplikasi

### Persyaratan Sistem
- **Web Server**: Apache (XAMPP/WAMP/LAMP)
- **Database**: MySQL/MariaDB
- **PHP**: Versi 7.4 atau lebih tinggi
- **Browser**: Modern web browser (Chrome, Firefox, Edge, Safari)

### Langkah-Langkah Instalasi

#### 1. Persiapan Environment
```bash
# Download dan install XAMPP
# Pastikan Apache dan MySQL aktif di XAMPP Control Panel
```

#### 2. Instalasi Aplikasi
1. **Clone/Download Source Code**
   - Extract file aplikasi ke direktori `C:\xampp\htdocs\arsip_digital\`
   
2. **Konfigurasi Database**
   - Buka phpMyAdmin di `http://localhost/phpmyadmin`
   - Buat database baru dengan nama `community_app`
   - Import file SQL: `community_app.sql` yang tersedia di root direktori

3. **Konfigurasi Koneksi Database**
   ```php
   // File: config/koneksi.php
   $host = "localhost";
   $user = "root";
   $pass = "";
   $db   = "community_app";
   ```

4. **Setup Direktori Upload**
   - Pastikan direktori `public/uploads/` memiliki permission write
   - Buat folder berikut jika belum ada:
     - `public/uploads/dokumen/`
     - `public/uploads/logo/`

#### 3. Menjalankan Aplikasi
1. Start Apache dan MySQL di XAMPP Control Panel
2. Buka browser dan akses: `http://localhost/arsip_digital`
3. Aplikasi akan redirect ke halaman login

#### 4. Login Default
- **Admin**:
  - Username: `admin`
  - Password: `admin123` (sesuai data di database)
- **User** (contoh):
  - Username: `yashi`
  - Password: (sesuai registrasi)

---

## b) Struktur Database

Database `community_app` terdiri dari 10 tabel utama:

### Entity Relationship Diagram (ERD)

```
┌─────────────────┐    ┌──────────────────┐    ┌─────────────────┐
│    settings     │    │      users       │    │    comments     │
├─────────────────┤    ├──────────────────┤    ├─────────────────┤
│ id (PK)         │    │ id (PK)          │    │ id (PK)         │
│ name (UNIQUE)   │    │ username (UNIQUE)│    │ event_id (FK)   │
│ value           │    │ email            │    │ user_id (FK)    │
└─────────────────┘    │ password         │    │ comment         │
                       │ role             │    │ created_at      │
                       │ created_at       │    └─────────────────┘
                       └──────────────────┘             │
                                │                      │
                                │                      │
        ┌─────────────────┐     │     ┌─────────────────┐
        │   categories    │     │     │     events      │
        ├─────────────────┤     │     ├─────────────────┤
        │ id (PK)         │     │     │ id (PK)         │
        │ name (UNIQUE)   │     │     │ title           │
        └─────────────────┘     │     │ description     │
                │               │     │ event_year      │
                │               │     │ created_by (FK) │
                │               │     │ created_at      │
                │               │     └─────────────────┘
                │               │              │
                │               │              │
        ┌─────────────────┐     │     ┌─────────────────┐
        │event_categories │     │     │   documents     │
        ├─────────────────┤     │     ├─────────────────┤
        │ event_id (PK,FK)│     │     │ id (PK)         │
        │category_id(PK,FK│     │     │ event_id (FK)   │
        └─────────────────┘     │     │ file_name       │
                                │     │ file_type       │
                                │     │ uploaded_by (FK)│
                                │     │ uploaded_at     │
                                │     └─────────────────┘
                                │
        ┌─────────────────┐     │     ┌─────────────────┐
        │ activity_logs   │     │     │ notifications   │
        ├─────────────────┤     │     ├─────────────────┤
        │ id (PK)         │     │     │ id (PK)         │
        │ user_id (FK)    │ ────┘     │ user_id (FK)    │
        │ activity        │           │ message         │
        │ created_at      │           │ is_read         │
        └─────────────────┘           │ created_at      │
                                      └─────────────────┘
                                               │
                                               │
                                      ┌─────────────────┐
                                      │    reports      │
                                      ├─────────────────┤
                                      │ id (PK)         │
                                      │ user_id (FK)    │
                                      │ report_date     │
                                      │ content         │
                                      └─────────────────┘
```

### Keterangan ERD:
- **PK**: Primary Key
- **FK**: Foreign Key
- **UNIQUE**: Constraint unik
- Garis menunjukkan relasi antar tabel

### Detail Tabel:

### 1. **users** - Tabel Pengguna
```sql
- id (Primary Key)
- username (Unique)
- email
- password (Encrypted)
- role (admin/user)
- created_at (Timestamp)
```

### 2. **events** - Tabel Kegiatan
```sql
- id (Primary Key)
- title
- description
- event_year
- created_by (Foreign Key → users.id)
- created_at (Timestamp)
```

### 3. **categories** - Tabel Kategori
```sql
- id (Primary Key)
- name (Unique)
```

### 4. **event_categories** - Tabel Relasi Event-Kategori
```sql
- event_id (Foreign Key → events.id)
- category_id (Foreign Key → categories.id)
- Primary Key: (event_id, category_id)
```

### 5. **documents** - Tabel Dokumen
```sql
- id (Primary Key)
- event_id (Foreign Key → events.id)
- file_name
- file_type
- uploaded_by (Foreign Key → users.id)
- uploaded_at (Timestamp)
```

### 6. **comments** - Tabel Komentar
```sql
- id (Primary Key)
- event_id (Foreign Key → events.id)
- user_id (Foreign Key → users.id)
- comment (Text)
- created_at (Timestamp)
```

### 7. **reports** - Tabel Laporan
```sql
- id (Primary Key)
- user_id (Foreign Key → users.id)
- report_date (DateTime)
- content (Text)
```

### 8. **notifications** - Tabel Notifikasi
```sql
- id (Primary Key)
- user_id (Foreign Key → users.id)
- message (Text)
- is_read (Boolean)
- created_at (Timestamp)
```

### 9. **activity_logs** - Tabel Log Aktivitas
```sql
- id (Primary Key)
- user_id (Foreign Key → users.id)
- activity (Text)
- created_at (Timestamp)
```

### 10. **settings** - Tabel Pengaturan Aplikasi
```sql
- id (Primary Key)
- name (Unique)
- value (Text)
```

### Relasi Antar Tabel
- **users** → **events** (1:N) - Satu user bisa membuat banyak event
- **events** → **documents** (1:N) - Satu event bisa memiliki banyak dokumen  
- **events** → **comments** (1:N) - Satu event bisa memiliki banyak komentar
- **events** → **event_categories** (M:N) - Many-to-Many melalui tabel pivot
- **categories** → **event_categories** (M:N) - Many-to-Many melalui tabel pivot
- **users** → **reports** (1:N) - Satu user bisa membuat banyak laporan
- **users** → **notifications** (1:N) - Satu user bisa memiliki banyak notifikasi
- **users** → **activity_logs** (1:N) - Satu user bisa memiliki banyak log aktivitas

---

## c) Cara Menggunakan Aplikasi

### Login dan Autentikasi

#### 1. Halaman Login
- Akses: `http://localhost/arsip_digital/login.php`
- Masukkan username dan password
- Sistem akan mengarahkan ke dashboard sesuai role

#### 2. Registrasi User Baru
- Akses: `http://localhost/arsip_digital/register.php`
- Isi form: Username, Email, Password, Konfirmasi Password
- User baru otomatis mendapat role "user"

### Navigasi Aplikasi

#### **Dashboard Admin**
Setelah login sebagai admin, Anda dapat mengakses:

1. **Dashboard** - Statistik aplikasi
   - Total pengguna
   - Total kegiatan
   - Total dokumen

2. **Manajemen Pengguna** (`/admin/user.php`)
   - Menambah user baru
   - Edit data user
   - Hapus user
   - Mengubah role user

3. **Manajemen Kegiatan** (`/admin/event.php`)
   - Membuat kegiatan baru
   - Edit/hapus kegiatan
   - Melihat daftar semua kegiatan

4. **Manajemen Kategori** (`/admin/kategori.php`)
   - Menambah kategori baru
   - Mengelola kategori kegiatan
   - Mengatur relasi event-kategori

5. **Manajemen Dokumen** (`/admin/dokumen.php`)
   - Upload dokumen untuk kegiatan
   - Download/hapus dokumen
   - Melihat semua dokumen

6. **Laporan** (`/admin/laporan.php`)
   - Melihat laporan dari user
   - Menghapus laporan

7. **Komentar** (`/admin/komentar.php`)
   - Moderasi komentar
   - Hapus komentar tidak pantas

8. **Notifikasi** (`/admin/notifikasi.php`)
   - Mengelola notifikasi sistem
   - Kirim notifikasi ke user

9. **Log Aktivitas** (`/admin/aktivitas.php`)
   - Melihat riwayat aktivitas user
   - Monitoring sistem

10. **Pengaturan** (`/admin/setting.php`)
    - Mengatur nama aplikasi
    - Upload logo organisasi
    - Konfigurasi aplikasi

#### **Dashboard User**
User biasa memiliki akses terbatas:

1. **Dashboard** - Statistik personal
   - Jumlah dokumen yang di-upload
   - Jumlah laporan yang dibuat

2. **Kegiatan** (`/user/event.php`)
   - Melihat daftar kegiatan
   - Memberikan komentar

3. **Dokumen** (`/user/dokumen.php`)
   - Upload dokumen ke kegiatan
   - Download dokumen
   - Melihat dokumen yang di-upload

4. **Laporan** (`/user/laporan.php`)
   - Membuat laporan
   - Melihat laporan yang dibuat

5. **Komentar** (`/user/komentar.php`)
   - Melihat dan mengelola komentar sendiri

6. **Notifikasi** (`/user/notifikasi.php`)
   - Melihat notifikasi dari admin
   - Tandai notifikasi sebagai dibaca

### Fitur Utama

#### 1. **Upload Dokumen**
- Pilih kegiatan yang sesuai
- Upload file (mendukung berbagai format)
- File akan disimpan di `public/uploads/dokumen/`

#### 2. **Sistem Komentar**
- User bisa memberikan komentar pada kegiatan
- Admin dapat memoderasi komentar

#### 3. **Sistem Laporan**
- User dapat membuat laporan
- Admin dapat melihat dan mengelola laporan

#### 4. **Sistem Notifikasi**
- Admin dapat mengirim notifikasi ke user
- User dapat melihat dan menandai notifikasi sebagai dibaca

#### 5. **Log Aktivitas**
- Sistem mencatat aktivitas login/logout user
- Admin dapat memantau aktivitas sistem

### Tips Penggunaan

1. **Keamanan**:
   - Selalu logout setelah selesai menggunakan aplikasi
   - Gunakan password yang kuat
   - Admin sebaiknya secara rutin memeriksa log aktivitas

2. **Manajemen File**:
   - Gunakan nama file yang deskriptif
   - Pastikan ukuran file tidak terlalu besar
   - Backup dokumen secara berkala

3. **Organisasi Data**:
   - Buat kategori yang sesuai dengan kebutuhan
   - Gunakan deskripsi yang jelas untuk setiap kegiatan
   - Kelompokkan kegiatan berdasarkan tahun

---

## Troubleshooting

### Masalah Umum:

1. **Error Database Connection**
   - Periksa konfigurasi di `config/koneksi.php`
   - Pastikan MySQL service berjalan
   - Pastikan database `community_app` sudah dibuat

2. **File Upload Gagal**
   - Periksa permission folder `public/uploads/`
   - Pastikan disk space cukup
   - Periksa setting `upload_max_filesize` di PHP

3. **Session Error**
   - Pastikan session PHP aktif
   - Clear browser cache dan cookies
   - Restart Apache service

4. **Tampilan Error**
   - Pastikan CSS dan Bootstrap terload dengan benar
   - Periksa koneksi internet untuk CDN
   - Clear browser cache

---

## Kontribusi dan Support

Untuk pertanyaan atau masalah teknis, silakan hubungi administrator sistem atau developer aplikasi.

---

**Terakhir diperbarui**: Juli 2025  
**Versi**: 1.0  
**Developer**: Tim Pengembang Arsip Digital
