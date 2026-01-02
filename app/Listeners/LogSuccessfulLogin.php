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
        $data = [
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
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
    }
}
