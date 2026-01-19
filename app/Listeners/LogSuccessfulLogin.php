<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use App\Models\LoginHistory;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct(private Request $request)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        try {
            // Deteksi IP dengan urutan prioritas yang aman
            $ip = $this->request->header('CF-Connecting-IP') 
                  ?? $this->request->header('X-Forwarded-For') 
                  ?? $this->request->ip();

            $agent = $this->request->userAgent() ?? '';
            $country = $this->request->header('CF-IPCountry');
            
            // Deteksi Sederhana Merk HP/Perangkat
            $device = "Unknown Perangkat";
            if (!$agent) {
                $device = "No User Agent";
            } elseif (preg_match('/(android)/i', $agent)) {
                $device = "Android";
                if (preg_match('/Android\s+[^;]+;\s+([^;)]+)/i', $agent, $matches)) {
                    $device .= " (" . trim($matches[1]) . ")";
                }
            } elseif (preg_match('/(iphone|ipad)/i', $agent)) {
                $device = "iPhone/iPad";
            } elseif (preg_match('/(windows)/i', $agent)) {
                $device = "Windows PC";
            } elseif (preg_match('/(macintosh|mac os x)/i', $agent)) {
                $device = "MacBook/iMac";
            }

            $data = [
                'ip_address' => $ip,
                'user_agent' => $device . ($country ? " [{$country}]" : "") . " | " . ($agent ?: 'Unknown'),
                'login_at' => now(),
            ];

            if ($event->user instanceof \App\Models\User) {
                $data['user_id'] = $event->user->id;
            } elseif ($event->user instanceof \App\Models\UserSiswa) {
                $data['user_siswa_id'] = $event->user->id;
            }

            if (isset($data['user_id']) || isset($data['user_siswa_id'])) {
                LoginHistory::create($data);
            }
        } catch (\Throwable $e) {
            // Jika ada error di logging, jangan gagalkan login utama user
            logger()->error("Gagal mencatat histori login: " . $e->getMessage());
        }
    }
}
