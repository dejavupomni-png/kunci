<?php

// 1. Interface yang lebih realistis dengan parameter tujuan
interface SaluranNotifikasi {
    public function kirim(string $tujuan, string $pesan): bool;
    public function getNamaSaluran(): string;
}

// 2. Implementasi untuk Email
class Email implements SaluranNotifikasi {
    public function getNamaSaluran(): string {
        return "Email";
    }

    public function kirim(string $tujuan, string $pesan): bool {
        // Simulasi validasi email sederhana
        if (!filter_var($tujuan, FILTER_VALIDATE_EMAIL)) {
            echo "[Gagal] Format email '{$tujuan}' tidak valid.\n";
            return false;
        }
        
        echo "[{$this->getNamaSaluran()}] Mengirim pesan ke {$tujuan}:\n";
        echo "Isi Pesan: \"{$pesan}\"\n";
        echo "Status: Berhasil Terkirim ✔️\n\n";
        return true;
    }
}

// 3. Implementasi untuk WhatsApp
class WhatsApp implements SaluranNotifikasi {
    public function getNamaSaluran(): string {
        return "WhatsApp";
    }

    public function kirim(string $tujuan, string $pesan): bool {
        echo "[{$this->getNamaSaluran()}] Mengirim pesan chat ke {$tujuan}:\n";
        echo "Isi Pesan: \"{$pesan}\"\n";
        echo "Status: Centang Dua (Terkirim) ✔️✔️\n\n";
        return true;
    }
}

// 4. Implementasi untuk SMS
class SMS implements SaluranNotifikasi {
    public function getNamaSaluran(): string {
        return "SMS";
    }

    public function kirim(string $tujuan, string $pesan): bool {
        echo "[{$this->getNamaSaluran()}] Mengirim teks ke {$tujuan}:\n";
        echo "Isi Pesan: \"{$pesan}\"\n";
        echo "Status: Terkirim ✔️\n\n";
        return true;
    }
}

// 5. Class Service yang menggunakan Dependency Injection (Inilah yang membuatnya sangat fleksibel)
class NotificationService {
    private SaluranNotifikasi $saluran;

    // Masukkan saluran apapun yang mengimplementasikan interface SaluranNotifikasi
    public function __construct(SaluranNotifikasi $saluran) {
        $this->saluran = $saluran;
    }

    // Bisa ganti saluran di tengah jalan
    public function setSaluran(SaluranNotifikasi $saluran): void {
        $this->saluran = $saluran;
    }

    public function broadcast(string $tujuan, string $pesan): void {
        echo "Sistem memproses notifikasi...\n";
        $this->saluran->kirim($tujuan, $pesan);
    }
}

// ==========================================
// CONTOH PENGGUNAAN (SKENARIO APLIKASI)
// ==========================================

// Skenario: User mendaftar, kirim notif via Email
$layananNotif = new NotificationService(new Email());
$layananNotif->broadcast("user@gmail.com", "Selamat datang di aplikasi kami!");

// Skenario: Ada promo diskon, ganti saluran ke WhatsApp untuk user yang sama
$layananNotif->setSaluran(new WhatsApp());
$layananNotif->broadcast("+6281234567890", "Flash sale diskon 50% khusus hari ini!");

// Skenario: OTP login, ganti saluran ke SMS
$layananNotif->setSaluran(new SMS());
$layananNotif->broadcast("+6281234567890", "Kode OTP Anda adalah: 458921. Jangan berikan kepada siapapun.");
