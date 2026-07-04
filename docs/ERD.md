# Model Data Fisik — TVMS (Tambang Vehicle Management System)

## Entity Relationship Diagram (ERD)

```
┌─────────────────────────────────┐
│             users               │
├─────────────────────────────────┤
│ PK  id              BIGINT      │
│     name            VARCHAR(255)│
│     email           VARCHAR(255)│  UNIQUE
│     password        VARCHAR(255)│
│     role            ENUM        │  'admin','approver'
│     failed_login_attempts TINYINT│
│     locked_until    TIMESTAMP   │  NULLABLE
│     remember_token  VARCHAR(100)│  NULLABLE
│     created_at      TIMESTAMP   │
│     updated_at      TIMESTAMP   │
└─────────────────────────────────┘
         |                |
         | 1              | 1
         |                |
         ↓ N              ↓ N
┌────────────────────┐   ┌──────────────────────────────────────┐
│     vehicles       │   │            bookings                  │
├────────────────────┤   ├──────────────────────────────────────┤
│ PK id  BIGINT      │   │ PK  id               BIGINT          │
│  plat_nomor        │   │ FK  vehicle_id        BIGINT  ──────→│ vehicles.id
│    VARCHAR(20)     │   │ FK  driver_id         BIGINT  ──────→│ drivers.id
│    UNIQUE          │   │ FK  approver_level_1_id BIGINT ─────→│ users.id
│  jenis ENUM        │   │ FK  approver_level_2_id BIGINT ─────→│ users.id
│  'angkutan_orang'  │   │     tanggal_mulai     DATE           │
│  'angkutan_barang' │   │     tanggal_selesai   DATE           │
│  status_kepemilikan│   │     keperluan         VARCHAR(255)   │
│    ENUM            │   │     konsumsi_bbm      DECIMAL(8,2)   │  NULLABLE
│  'milik_sendiri'   │   │     status_pembokingan ENUM          │
│  'sewa'            │   │       'pending'                      │
│  created_at        │   │       'disetujui_level_1'            │
│  updated_at        │   │       'disetujui_final'              │
└────────────────────┘   │       'ditolak'                      │
         |               │       'dibatalkan'                   │
         | 1             │     created_at        TIMESTAMP      │
         |               │     updated_at        TIMESTAMP      │
         ↓ N             │                                      │
┌──────────────────────┐ │ IDX: idx_vehicle_dates               │
│   vehicle_services   │ │      (vehicle_id, tgl_mulai, tgl_selesai) │
├──────────────────────┤ │ IDX: idx_driver_dates                │
│ PK id  BIGINT        │ │      (driver_id, tgl_mulai, tgl_selesai)  │
│ FK vehicle_id BIGINT │ └──────────────────────────────────────┘
│   tanggal_service    │          |            |
│     DATE             │          | 1          | 1
│   deskripsi TEXT     │          |            |
│   created_at         │          ↓ N          ↓ N
│   updated_at         │ ┌──────────────┐  ┌──────────────────────────┐
└──────────────────────┘ │   approvals  │  │      activity_logs       │
                         ├──────────────┤  ├──────────────────────────┤
┌──────────────────────┐ │ PK id BIGINT │  │ PK  id         BIGINT    │
│       drivers        │ │ FK booking_id│  │ FK  user_id    BIGINT    │  NULLABLE
├──────────────────────┤ │   BIGINT     │  │     aksi       VARCHAR(100)│
│ PK id  BIGINT        │ │ FK approver_id│ │     deskripsi  TEXT      │
│  nama_driver         │ │   BIGINT     │  │     loggable_type        │
│   VARCHAR(100) UNIQUE│ │   level      │  │      VARCHAR(255) NULLABLE│
│  status ENUM         │ │   TINYINT    │  │     loggable_id          │
│  'tersedia','bertugas'│ │   status ENUM│  │      BIGINT NULLABLE     │
│  created_at          │ │  'pending'   │  │     data_lama  TEXT NULLABLE│
│  updated_at          │ │  'disetujui' │  │     data_baru  TEXT NULLABLE│
└──────────────────────┘ │  'ditolak'   │  │     created_at TIMESTAMP │
                         │  catatan TEXT│  │     updated_at TIMESTAMP │
                         │   NULLABLE   │  └──────────────────────────┘
                         │  created_at  │
                         │  updated_at  │
                         │ UNIQUE:      │
                         │  (booking_id,│
                         │   level)     │
                         └──────────────┘
```

---

## Daftar Tabel & Deskripsi Kolom

### 1. `users`
Menyimpan data semua pengguna sistem (admin & approver).

| Kolom                  | Tipe            | Constraint         | Keterangan                        |
|------------------------|-----------------|--------------------|-----------------------------------|
| id                     | BIGINT UNSIGNED | PK, AUTO_INCREMENT |                                   |
| name                   | VARCHAR(255)    | NOT NULL           | Nama lengkap pengguna             |
| email                  | VARCHAR(255)    | UNIQUE, NOT NULL   | Digunakan sebagai username login  |
| password               | VARCHAR(255)    | NOT NULL           | Bcrypt hash                       |
| role                   | ENUM            | NOT NULL           | `admin` atau `approver`           |
| failed_login_attempts  | TINYINT UNSIGNED| DEFAULT 0          | Counter percobaan login gagal     |
| locked_until           | TIMESTAMP       | NULLABLE           | Waktu akun terkunci               |
| remember_token         | VARCHAR(100)    | NULLABLE           | Laravel remember-me token         |
| created_at / updated_at| TIMESTAMP       |                    |                                   |

---

### 2. `vehicles`
Menyimpan data kendaraan operasional tambang.

| Kolom              | Tipe         | Constraint         | Keterangan                                |
|--------------------|--------------|--------------------|-------------------------------------------|
| id                 | BIGINT UNSIGNED | PK               |                                           |
| plat_nomor         | VARCHAR(20)  | UNIQUE, NOT NULL   | Nomor plat kendaraan                      |
| jenis              | ENUM         | NOT NULL           | `angkutan_orang` / `angkutan_barang`      |
| status_kepemilikan | ENUM         | NOT NULL           | `milik_sendiri` / `sewa`                  |
| created_at / updated_at | TIMESTAMP |                  |                                           |

---

### 3. `drivers`
Menyimpan data pengemudi.

| Kolom        | Tipe            | Constraint         | Keterangan                      |
|--------------|-----------------|--------------------|---------------------------------|
| id           | BIGINT UNSIGNED | PK                 |                                 |
| nama_driver  | VARCHAR(100)    | UNIQUE, NOT NULL   | Nama lengkap pengemudi          |
| status       | ENUM            | DEFAULT `tersedia` | `tersedia` / `bertugas`         |
| created_at / updated_at | TIMESTAMP |               |                                 |

---

### 4. `bookings`
Inti sistem — menyimpan data pemesanan kendaraan.

| Kolom                 | Tipe             | Constraint          | Keterangan                                     |
|-----------------------|------------------|---------------------|------------------------------------------------|
| id                    | BIGINT UNSIGNED  | PK                  |                                                |
| vehicle_id            | BIGINT UNSIGNED  | FK → vehicles.id    | Kendaraan yang dipesan                         |
| driver_id             | BIGINT UNSIGNED  | FK → drivers.id     | Pengemudi yang ditugaskan                      |
| approver_level_1_id   | BIGINT UNSIGNED  | FK → users.id       | Approver Level 1                               |
| approver_level_2_id   | BIGINT UNSIGNED  | FK → users.id       | Approver Level 2                               |
| tanggal_mulai         | DATE             | NOT NULL            | Tanggal mulai pemakaian                        |
| tanggal_selesai       | DATE             | NOT NULL            | Tanggal selesai pemakaian                      |
| keperluan             | VARCHAR(255)     | NOT NULL            | Deskripsi keperluan perjalanan                 |
| konsumsi_bbm          | DECIMAL(8,2)     | NULLABLE            | Konsumsi BBM (liter), diisi setelah selesai    |
| status_pembokingan    | ENUM             | DEFAULT `pending`   | Lihat nilai enum di bawah                      |
| created_at / updated_at | TIMESTAMP      |                     |                                                |

**Nilai status_pembokingan:**
- `pending` — baru dibuat, menunggu persetujuan L1
- `disetujui_level_1` — L1 sudah setuju, menunggu L2
- `disetujui_final` — L2 sudah setuju, booking aktif
- `ditolak` — salah satu approver menolak
- `dibatalkan` — dibatalkan oleh admin sebelum disetujui final

**Indeks komposit:**
- `idx_vehicle_dates (vehicle_id, tanggal_mulai, tanggal_selesai)` — deteksi konflik jadwal kendaraan
- `idx_driver_dates (driver_id, tanggal_mulai, tanggal_selesai)` — deteksi konflik jadwal pengemudi

---

### 5. `approvals`
Menyimpan rekam jejak persetujuan per level per booking.

| Kolom       | Tipe            | Constraint                        | Keterangan                            |
|-------------|-----------------|-----------------------------------|---------------------------------------|
| id          | BIGINT UNSIGNED | PK                                |                                       |
| booking_id  | BIGINT UNSIGNED | FK → bookings.id, CASCADE DELETE  | Booking yang diproses                 |
| approver_id | BIGINT UNSIGNED | FK → users.id, CASCADE DELETE     | User yang bertindak sebagai approver  |
| level       | TINYINT UNSIGNED| NOT NULL                          | `1` atau `2`                          |
| status      | ENUM            | DEFAULT `pending`                 | `pending` / `disetujui` / `ditolak`  |
| catatan     | TEXT            | NULLABLE                          | Catatan alasan penolakan              |
| created_at / updated_at | TIMESTAMP |                             |                                       |

**Unique constraint:** `(booking_id, level)` — satu record per booking per level.

---

### 6. `activity_logs`
Audit trail otomatis setiap perubahan status booking & approval.

| Kolom          | Tipe            | Constraint          | Keterangan                                        |
|----------------|-----------------|---------------------|---------------------------------------------------|
| id             | BIGINT UNSIGNED | PK                  |                                                   |
| user_id        | BIGINT UNSIGNED | FK → users.id NULLABLE, nullOnDelete | User yang melakukan aksi    |
| aksi           | VARCHAR(100)    | NOT NULL            | Kode aksi: `booking.created`, `approval.status_changed`, dll. |
| deskripsi      | TEXT            | NOT NULL            | Deskripsi aksi dalam bahasa Indonesia             |
| loggable_type  | VARCHAR(255)    | NULLABLE            | Class model yang terkait (polymorphic)            |
| loggable_id    | BIGINT UNSIGNED | NULLABLE            | ID record yang terkait                            |
| data_lama      | TEXT (JSON)     | NULLABLE            | Data sebelum perubahan                            |
| data_baru      | TEXT (JSON)     | NULLABLE            | Data setelah perubahan                            |
| created_at / updated_at | TIMESTAMP |                  |                                                   |

---

### 7. `vehicle_services`
Menyimpan riwayat dan jadwal servis per kendaraan.

| Kolom           | Tipe            | Constraint                       | Keterangan                     |
|-----------------|-----------------|----------------------------------|--------------------------------|
| id              | BIGINT UNSIGNED | PK                               |                                |
| vehicle_id      | BIGINT UNSIGNED | FK → vehicles.id, CASCADE DELETE | Kendaraan yang diservis        |
| tanggal_service | DATE            | NOT NULL                         | Tanggal pelaksanaan servis     |
| deskripsi       | TEXT            | NOT NULL                         | Jenis/detail pekerjaan servis  |
| created_at / updated_at | TIMESTAMP |                              |                                |

---

## Relasi Antar Tabel

```
users           ──1:N──  bookings        (sebagai approver_level_1_id)
users           ──1:N──  bookings        (sebagai approver_level_2_id)
users           ──1:N──  approvals       (sebagai approver_id)
users           ──1:N──  activity_logs   (sebagai user_id)

vehicles        ──1:N──  bookings
vehicles        ──1:N──  vehicle_services

drivers         ──1:N──  bookings

bookings        ──1:N──  approvals
bookings        ──1:N──  activity_logs   (polymorphic via loggable_*)
approvals       ──1:N──  activity_logs   (polymorphic via loggable_*)
```
