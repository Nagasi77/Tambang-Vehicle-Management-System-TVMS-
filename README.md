# TVMS — Tambang Vehicle Management System

Sistem informasi manajemen kendaraan operasional untuk perusahaan tambang. Dibangun dengan Laravel 10 dan Tailwind CSS, mendukung pemesanan kendaraan, alur persetujuan dua level, jadwal servis, laporan Excel, dan log aktivitas.

---

## Akun Pengguna Default

Jalankan seeder terlebih dahulu (`php artisan db:seed`), kemudian gunakan kredensial berikut:

| Role     | Email                  | Password     |
|----------|------------------------|--------------|
| Admin    | admin@tvms.com         | password123  |
| Approver | approver1@tvms.com     | password123  |
| Approver | approver2@tvms.com     | password123  |

---

## Persyaratan Sistem

| Komponen   | Versi          |
|------------|----------------|
| PHP        | ^8.2           |
| Laravel    | ^11.0          |
| MySQL      | 8.0+           |
| Composer   | 2.x            |
| Node.js    | (opsional, Tailwind via CDN) |

---

## Framework & Library

| Library              | Versi   | Fungsi                              |
|----------------------|---------|-------------------------------------|
| Laravel Framework    | ^11.0   | Backend MVC utama                   |
| Maatwebsite/Excel    | 3.1.58  | Export laporan ke file Excel (.xlsx)|
| Tailwind CSS         | CDN     | Styling antarmuka (utility-first)   |
| SweetAlert2          | CDN v11 | Modal konfirmasi hapus data         |
| Chart.js             | CDN     | Grafik pemakaian kendaraan          |
| Laravel Sanctum      | ^3.3    | Token API (scaffolding)             |

---

## Panduan Instalasi & Penggunaan

### 1. Clone Repositori

```bash
git clone https://github.com/yourusername/Tambang-Vehicle-Management-System-TVMS-.git
cd Tambang-Vehicle-Management-System-TVMS-/tvms
```

### 2. Install Dependensi PHP

```bash
composer install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env` dan sesuaikan konfigurasi database:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tvms
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi & Seed Database

```bash
php artisan migrate --seed
```

### 5. Jalankan Aplikasi

```bash
php artisan serve
```

Akses di browser: **http://localhost:8000**

---

## Panduan Penggunaan Aplikasi

### Login
Buka `http://localhost:8000` → sistem otomatis redirect ke halaman login. Masukkan email dan password sesuai tabel akun di atas.

### Role Admin
Setelah login sebagai admin, tersedia menu navigasi:

| Menu           | Fungsi                                                |
|----------------|-------------------------------------------------------|
| Dashboard      | Ringkasan statistik + grafik pemakaian kendaraan + log aktivitas |
| Kendaraan      | CRUD kendaraan (plat nomor, jenis, kepemilikan)       |
| Pengemudi      | CRUD pengemudi + filter status tersedia/bertugas      |
| Pemesanan      | Buat/kelola booking kendaraan + filter + batalkan     |
| Jadwal Servis  | Ringkasan & pencarian riwayat servis semua kendaraan  |
| Laporan        | Export data pemesanan ke Excel berdasarkan rentang tanggal |

#### Membuat Pemesanan Baru
1. Klik **Pemesanan → Buat Booking**
2. Pilih kendaraan, pengemudi, tanggal mulai/selesai, keperluan
3. Pilih Approver Level 1 dan Level 2
4. Klik **Simpan** → status otomatis `pending`

#### Melihat Laporan Excel
1. Klik **Laporan**
2. Pilih rentang tanggal (maks 366 hari)
3. Klik **Export Excel** → file `.xlsx` otomatis terunduh

### Role Approver
Setelah login sebagai approver, tersedia menu:

| Menu      | Fungsi                                           |
|-----------|--------------------------------------------------|
| Pengajuan | Daftar booking yang perlu disetujui/ditolak      |

#### Alur Persetujuan
1. Approver Level 1 menerima notifikasi pengajuan → klik **Detail** → klik **Setujui** atau tulis catatan lalu **Tolak**
2. Setelah Level 1 menyetujui, Approver Level 2 mendapat giliran untuk tindakan yang sama
3. Jika Level 2 menyetujui → status booking berubah menjadi `disetujui_final`
4. Jika salah satu level menolak → status booking berubah menjadi `ditolak`

---

## Struktur Role & Akses

```
Admin    → Dashboard, Kendaraan, Pengemudi, Pemesanan, Jadwal Servis, Laporan
Approver → Pengajuan (daftar + detail + approve/reject)
```

Login throttle: maksimal **5 percobaan per 15 menit** per IP.

---

## Fitur Utama

- **Manajemen Kendaraan** — CRUD lengkap, filter jenis & kepemilikan, pencarian plat nomor
- **Manajemen Pengemudi** — CRUD, filter status, proteksi hapus jika sedang bertugas
- **Pemesanan Kendaraan** — Booking dengan validasi konflik jadwal kendaraan & pengemudi
- **Alur Persetujuan 2 Level** — L1 harus approve sebelum L2 dapat bertindak
- **Jadwal Servis** — Riwayat servis per kendaraan + ringkasan global lintas kendaraan
- **Laporan Excel** — Export data pemesanan berdasarkan rentang tanggal
- **Log Aktivitas** — Setiap perubahan status booking & approval tercatat otomatis
- **Modal Konfirmasi** — SweetAlert2 untuk semua aksi hapus/batalkan

---

## Dokumen Pendukung

| Dokumen                         | Lokasi                          |
|---------------------------------|----------------------------------|
| Model Data Fisik (ERD)          | `docs/ERD.md`                   |
| Diagram Aktivitas Pemesanan     | `docs/activity-diagram.md`      |

---

## Lisensi

MIT License
