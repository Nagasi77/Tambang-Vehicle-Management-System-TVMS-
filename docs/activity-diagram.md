# Diagram Aktivitas — Fitur Pemesanan Kendaraan (TVMS)

## Alur Utama: Pemesanan Kendaraan dengan Persetujuan 2 Level

```
┌──────────────────────────────────────────────────────────────────────────────┐
│                    DIAGRAM AKTIVITAS PEMESANAN KENDARAAN                     │
│                  Tambang Vehicle Management System (TVMS)                    │
└──────────────────────────────────────────────────────────────────────────────┘

      ADMIN                SISTEM                APPROVER L1          APPROVER L2
        │                    │                        │                    │
        ●                    │                        │                    │
        │ (Mulai)            │                        │                    │
        ↓                    │                        │                    │
  ┌──────────┐               │                        │                    │
  │ Login ke │               │                        │                    │
  │  Sistem  │               │                        │                    │
  └────┬─────┘               │                        │                    │
       ↓                     │                        │                    │
  ┌──────────────────┐        │                        │                    │
  │ Buka Halaman     │        │                        │                    │
  │ Buat Pemesanan   │        │                        │                    │
  └────────┬─────────┘        │                        │                    │
           ↓                  │                        │                    │
  ┌────────────────────────┐  │                        │                    │
  │ Isi Form Pemesanan:    │  │                        │                    │
  │ - Pilih Kendaraan      │  │                        │                    │
  │ - Pilih Pengemudi      │  │                        │                    │
  │ - Tanggal Mulai/Selesai│  │                        │                    │
  │ - Keperluan            │  │                        │                    │
  │ - Pilih Approver L1    │  │                        │                    │
  │ - Pilih Approver L2    │  │                        │                    │
  └────────────┬───────────┘  │                        │                    │
               ↓               │                        │                    │
          ┌────────┐           │                        │                    │
          │ Submit │───────────→                        │                    │
          └────────┘           │                        │                    │
                               ↓                        │                    │
                    ┌──────────────────────┐             │                    │
                    │ Validasi Form        │             │                    │
                    │ - Field wajib terisi │             │                    │
                    │ - tgl_selesai ≥      │             │                    │
                    │   tgl_mulai          │             │                    │
                    └──────────┬───────────┘             │                    │
                               │                         │                    │
                    ┌──────────┴───────────┐             │                    │
                    │ Validasi Konflik      │             │                    │
                    │ Jadwal Kendaraan      │             │                    │
                    │ & Pengemudi          │             │                    │
                    └──────────┬───────────┘             │                    │
                               │                         │                    │
                    ┌──────────┴──────────┐              │                    │
                    ◇  Ada konflik?        │              │                    │
                    └───┬──────────────┬──┘              │                    │
                    Ya  │              │ Tidak            │                    │
                        ↓              ↓                  │                    │
               ┌─────────────┐  ┌──────────────────────┐ │                    │
               │ Tampilkan   │  │ Simpan Booking        │ │                    │
               │ Pesan Error │  │ status = 'pending'    │ │                    │
               │ ke Admin    │  │                       │ │                    │
               └──────┬──────┘  │ Buat record Approvals │ │                    │
                      │         │ Level 1 & Level 2     │ │                    │
                      ↓         │ (status = 'pending')  │ │                    │
               ┌─────────────┐  │                       │ │                    │
               │  Kembali ke │  │ Catat ActivityLog     │ │                    │
               │ Form Isian  │  │ booking.created       │ │                    │
               └─────────────┘  └───────────┬───────────┘ │                    │
                                            │               │                    │
                                            ↓               │                    │
                                ┌──────────────────────┐    │                    │
                                │ Booking tampil di     │    │                    │
                                │ daftar Pengajuan      │────→                   │
                                │ Approver Level 1      │    │                    │
                                └───────────────────────┘    │                    │
                                                             ↓                    │
                                                   ┌──────────────────┐          │
                                                   │ Buka Detail      │          │
                                                   │ Pengajuan        │          │
                                                   └────────┬─────────┘          │
                                                            │                    │
                                                   ┌────────┴──────┐             │
                                                   ◇  Keputusan L1?│             │
                                                   └───┬───────────┘             │
                                              Setujui  │  Tolak                  │
                                                   ┌───┴────┐                   │
                                                   ↓        ↓                   │
                                        ┌──────────────┐  ┌──────────────────┐  │
                                        │ Status booking│  │ Isi catatan      │  │
                                        │ = 'disetujui_ │  │ penolakan (wajib)│  │
                                        │   level_1'    │  └───────┬──────────┘  │
                                        │               │          ↓             │
                                        │ Catat Log     │  ┌──────────────────┐  │
                                        │ approval.     │  │ Status booking   │  │
                                        │ status_changed│  │ = 'ditolak'      │  │
                                        └───────┬───────┘  │                  │  │
                                                │           │ Status approval  │  │
                                                │           │ L1 = 'ditolak'   │  │
                                                │           │                  │  │
                                                │           │ Catat ActivityLog│  │
                                                │           └──────────────────┘  │
                                                │                    │             │
                                                ↓                    ↓             │
                                    ┌────────────────────┐   ┌──────────────┐     │
                                    │ Booking tampil di  │   │  ● (Selesai) │     │
                                    │ daftar Pengajuan   │   │  (Ditolak)   │     │
                                    │ Approver Level 2   │───→─────────────────────→
                                    └────────────────────┘                       │
                                                                                  ↓
                                                                       ┌──────────────────┐
                                                                       │ Buka Detail      │
                                                                       │ Pengajuan        │
                                                                       └────────┬─────────┘
                                                                                │
                                                                       ┌────────┴──────┐
                                                                       ◇  Keputusan L2?│
                                                                       └───┬───────────┘
                                                                  Setujui  │  Tolak
                                                                       ┌───┴────┐
                                                                       ↓        ↓
                                                            ┌─────────────────┐  ┌───────────────┐
                                                            │ Status booking  │  │ Isi catatan   │
                                                            │ = 'disetujui_   │  │ penolakan     │
                                                            │   final'        │  └──────┬────────┘
                                                            │                 │         ↓
                                                            │ Status approval │  ┌───────────────┐
                                                            │ L2 = 'disetujui'│  │Status booking │
                                                            │                 │  │= 'ditolak'    │
                                                            │ Catat Log       │  │               │
                                                            │ approval.       │  │Catat Log      │
                                                            │ status_changed  │  └──────┬────────┘
                                                            └────────┬────────┘         │
                                                                     │                  │
                                                                     ↓                  ↓
                                                              ┌─────────────┐    ┌─────────────┐
                                                              │ Booking     │    │ Booking     │
                                                              │ AKTIF       │    │ DITOLAK     │
                                                              │ ● (Selesai) │    │ ● (Selesai) │
                                                              └─────────────┘    └─────────────┘
```

---

## Alur Pembatalan Booking (Admin)

```
        ADMIN                    SISTEM
          │                        │
          ●                        │
          ↓                        │
  ┌───────────────────┐            │
  │ Buka Daftar       │            │
  │ Pemesanan         │            │
  └────────┬──────────┘            │
           ↓                       │
  ┌───────────────────┐            │
  │ Klik tombol       │            │
  │ "Batal" pada      │            │
  │ booking berstatus │            │
  │ pending /         │            │
  │ disetujui_level_1 │            │
  └────────┬──────────┘            │
           ↓                       │
  ┌───────────────────┐            │
  │ Modal Konfirmasi  │            │
  │ SweetAlert2       │            │
  └────────┬──────────┘            │
           │                       │
  ┌────────┴────────┐              │
  ◇  Konfirmasi?     │              │
  └───┬─────────────┘              │
  Ya  │       Tidak                │
      ↓           ↓                │
      │     ┌──────────────┐       │
      │     │ Tidak ada    │       │
      │     │ perubahan    │       │
      │     └──────────────┘       │
      │                            │
      └──────────────→             │
                     ↓             │
          ┌──────────────────────┐ │
          │ Status booking       │ │
          │ = 'dibatalkan'       │ │
          │                      │ │
          │ Catat ActivityLog    │ │
          │ booking.status_      │ │
          │ changed              │ │
          └──────────┬───────────┘ │
                     ↓             │
          ┌──────────────────────┐ │
          │ Redirect ke daftar   │ │
          │ pemesanan dengan     │ │
          │ flash success        │ │
          └──────────────────────┘ │
                     │             │
                     ●             │
                  (Selesai)        │
```

---

## Ringkasan Status Pemesanan

```
                         ┌─────────┐
                    ●───→│ PENDING │
                         └────┬────┘
                              │
               ┌──────────────┴──────────────┐
               ↓                             ↓
        ┌─────────────┐              ┌──────────────┐
        │ DISETUJUI   │              │   DITOLAK    │
        │  LEVEL 1    │              │  (L1 tolak)  │
        └──────┬──────┘              └──────────────┘
               │
         ┌─────┴──────────────────────┐
         │                            │
         ↓                            ↓
  ┌─────────────────┐         ┌──────────────┐
  │ DISETUJUI FINAL │         │   DITOLAK    │
  │  (Booking aktif)│         │  (L2 tolak)  │
  └─────────────────┘         └──────────────┘

  + DIBATALKAN (dapat terjadi dari PENDING atau DISETUJUI_LEVEL_1 oleh admin)
```
