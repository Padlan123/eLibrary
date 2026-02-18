<?php

namespace App\Traits;

use Illuminate\Support\Facades\RateLimiter;

trait WithRateLimiting
{
    protected function checkRateLimit($aksi, int $max, int $jedaWaktu): Bool
    {
        $key = "{$aksi}:" . request()->ip();

        if (RateLimiter::tooManyAttempts($key, $max)) {

            $waktu = RateLimiter::availableIn($key);

            $this->addError(
                'throttle',
                "Terlalu banyak percobaan. Coba lagi dalam {$waktu} detik."
            );

            return false;
        }
        RateLimiter::hit($key, $jedaWaktu);
        return true;
    }

    protected function resetRateLimit($aksi): void
    {
        $key = "{$aksi}:" . request()->ip();
        RateLimiter::clear($key);
    }
}
