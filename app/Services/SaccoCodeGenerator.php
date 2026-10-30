<?php

namespace App\Services;

use App\Models\Sacco;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class SaccoCodeGenerator
{
    /**
     * Prefix for SACCO codes
     */
    const PREFIX = '09';

    /**
     * Maximum attempts for primary format before fallback
     */
    const MAX_PRIMARY_ATTEMPTS = 50;

    /**
     * Generate a unique SACCO code in format '09XXXX' (6 chars total)
     * Uses database transaction with row locking to prevent race conditions.
     *
     * @return string Unique code like '091234'
     * @throws Exception if unable to generate unique code after all attempts
     */
    public function generate(): string
    {
        return DB::transaction(function () {
            // Try primary format: 09XXXX (4 random digits = 9000 combinations)
            for ($attempt = 1; $attempt <= self::MAX_PRIMARY_ATTEMPTS; $attempt++) {
                $code = self::PREFIX . str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

                // Use SELECT FOR UPDATE equivalent: lock the row if it exists
                $exists = Sacco::where('code', $code)->lockForUpdate()->exists();

                if (!$exists) {
                    return $code;
                }
            }

            // Fallback format: 09XXXXXX (6 random digits = 900,000 combinations)
            // This should practically never collide
            for ($attempt = 1; $attempt <= 100; $attempt++) {
                $code = self::PREFIX . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

                $exists = Sacco::where('code', $code)->lockForUpdate()->exists();

                if (!$exists) {
                    return $code;
                }
            }

            // Ultimate fallback: use timestamp to guarantee uniqueness
            // Format: 09 + last 6 digits of microtime
            $microtime = (int)(microtime(true) * 1000000);
            $code = self::PREFIX . str_pad($microtime % 1000000, 6, '0', STR_PAD_LEFT);

            // Final verification (should never fail)
            if (Sacco::where('code', $code)->exists()) {
                throw new Exception('Unable to generate unique SACCO code after all fallback attempts');
            }

            return $code;
        });
    }

    /**
     * Generate a unique code for RequestSacco (different format: 8-char alphanumeric)
     *
     * @return string Unique code like 'A1B2C3D4'
     */
    public function generateRequestCode(): string
    {
        return DB::transaction(function () {
            for ($attempt = 1; $attempt <= self::MAX_PRIMARY_ATTEMPTS; $attempt++) {
                $code = strtoupper(Str::random(8));

                // Note: RequestSacco uses different table, but same pattern
                $exists = \App\Models\RequestSacco::where('code', $code)->lockForUpdate()->exists();

                if (!$exists) {
                    return $code;
                }
            }

            // Fallback with timestamp
            $code = strtoupper(Str::random(8)) . time();

            if (\App\Models\RequestSacco::where('code', $code)->exists()) {
                throw new Exception('Unable to generate unique RequestSacco code');
            }

            return $code;
        });
    }

    /**
     * Validate that a code follows the expected SACCO format
     *
     * @param string $code
     * @return bool
     */
    public function isValidFormat(string $code): bool
    {
        return preg_match('/^09\d{4,6}$/', $code) === 1;
    }

    /**
     * Check if a code is available (without reserving it)
     *
     * @param string $code
     * @return bool
     */
    public function isAvailable(string $code): bool
    {
        return !Sacco::where('code', $code)->exists();
    }
}