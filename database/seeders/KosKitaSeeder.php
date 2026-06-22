<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\MaintenanceRequest;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Room;
use App\Models\TenantProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class KosKitaSeeder extends Seeder
{
    public function run(): void
    {
        $room = Room::create([
            'number' => '01A',
            'type' => 'standard',
            'price' => 1500000,
            'status' => 'occupied',
        ]);

        $user = User::where('email', 'user@gmail.com')->first();

        if (! $user) {
            return;
        }

        TenantProfile::create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'phone' => '0812-3456-7890',
            'identity_number' => '1234567890123456',
            'birth_date' => '1995-08-15',
            'birth_place' => 'Jakarta',
            'address' => 'Jalan Pendidikan No. 123, Jakarta Selatan',
            'occupation' => 'Mahasiswa - Universitas Indonesia',
            'parent_name' => 'Siti Rahmah',
            'parent_phone' => '0811-2345-6789',
            'lease_start' => '2024-01-01',
            'lease_end' => '2025-01-01',
            'deposit_amount' => 3000000,
            'deposit_date' => '2024-01-01',
            'deposit_status' => 'tersimpan',
            'deposit_notes' => 'Jaminan akan dikembalikan jika tidak ada kerusakan pada saat pindah',
        ]);

        Payment::insert([
            
         
            
        ]);

        MaintenanceRequest::insert([
            [
                'user_id' => $user->id,
                'title' => 'Pintu Kamar Sulit Ditutup',
                'category' => 'Furniture',
                'description' => 'Pintu kamar saya sulit untuk ditutup dengan rapat. Mohon diperbaiki agar dapat ditutup dengan lancar',
                'priority' => 'normal',
                'status' => 'pending',
                'submitted_at' => '2024-11-20',
                'completed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'title' => 'Lampu Kamar Mati',
                'category' => 'Kelistrikan',
                'description' => 'Salah satu lampu di kamar mati dan tidak bisa dinyalakan. Sudah diganti dengan lampu baru',
                'priority' => 'normal',
                'status' => 'completed',
                'submitted_at' => '2024-11-15',
                'completed_at' => '2024-11-17',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'title' => 'Perbaikan Saluran Air Kamar Mandi',
                'category' => 'Plumbing (Air)',
                'description' => 'Saluran air kamar mandi tidak lancar. Sudah dibersihkan dan sekarang berfungsi normal',
                'priority' => 'normal',
                'status' => 'completed',
                'submitted_at' => '2024-11-10',
                'completed_at' => '2024-11-12',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Notification::insert([
          
            [
                'user_id' => $user->id,
                'title' => 'Pengumuman Maintenance Kamar',
                'message' => 'Akan ada pembersihan dan perawatan kamar dan area umum pada hari Minggu tanggal 24 November pukul 10:00-12:00. Mohon tetap berada di kamar atau keluar untuk memudahkan proses maintenance',
                'type' => 'info',
                'is_read' => false,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'user_id' => $user->id,
                'title' => 'Pengingat Pembayaran Bulan Depan',
                'message' => 'Pembayaran bulan Desember 2024 akan jatuh tempo pada 01 Desember 2024. Pastikan melakukan pembayaran sebelum jatuh tempo untuk menghindari denda keterlambatan',
                'type' => 'warning',
                'is_read' => false,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'user_id' => $user->id,
                'title' => 'Update Peraturan Kos',
                'message' => 'Telah ada update mengenai peraturan kos terbaru. Mohon baca dan pahami peraturan yang berlaku di kos-kosan kami',
                'type' => 'info',
                'is_read' => false,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'user_id' => $user->id,
                'title' => 'Verifikasi Data Pribadi Selesai',
                'message' => 'Data pribadi Anda telah berhasil diverifikasi oleh sistem kami. Anda sekarang dapat menggunakan semua fitur dengan lengkap',
                'type' => 'info',
                'is_read' => false,
                'created_at' => now()->subWeek(),
                'updated_at' => now()->subWeek(),
            ],
        ]);

        ActivityLog::insert([
           
            [
                'user_id' => $user->id,
                'activity_type' => 'Maintenance',
                'description' => 'Ajukan perbaikan: Pintu sulit ditutup',
                'status' => 'Pending',
                'activity_date' => '2024-11-20',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'activity_type' => 'Maintenance',
                'description' => 'Perbaikan lampu kamar selesai',
                'status' => 'Selesai',
                'activity_date' => '2024-11-17',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        
       
            [
                'user_id' => $user->id,
                'activity_type' => 'Maintenance',
                'description' => 'Perbaikan saluran air selesai',
                'status' => 'Selesai',
                'activity_date' => '2024-11-12',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'activity_type' => 'Maintenance',
                'description' => 'Ajukan perbaikan: Saluran air tidak lancar',
                'status' => 'Selesai',
                'activity_date' => '2024-11-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
          
        ]);
    }
}
