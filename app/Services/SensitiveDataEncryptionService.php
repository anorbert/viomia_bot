<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class SensitiveDataEncryptionService
{
    /**
     * Encrypt sensitive data
     */
    public function encrypt($data)
    {
        try {
            return Crypt::encryptString($data);
        } catch (\Exception $e) {
            Log::error('Encryption error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Decrypt sensitive data
     */
    public function decrypt($encryptedData)
    {
        try {
            return Crypt::decryptString($encryptedData);
        } catch (\Exception $e) {
            Log::error('Decryption error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Hash sensitive data (one-way, for comparison only)
     */
    public function hash($data)
    {
        return hash('sha256', $data);
    }

    /**
     * Mask phone number for display: +250 123 ****789
     */
    public function maskPhoneNumber($country_code, $phone_number)
    {
        $masked = substr($phone_number, 0, 3) . ' ****' . substr($phone_number, -3);
        return $country_code . ' ' . $masked;
    }

    /**
     * Mask account name for display: Jo*** J***
     */
    public function maskAccountName($name)
    {
        $parts = explode(' ', $name);
        $masked = [];

        foreach ($parts as $part) {
            if (strlen($part) > 2) {
                $masked[] = substr($part, 0, 2) . str_repeat('*', strlen($part) - 2);
            } else {
                $masked[] = str_repeat('*', strlen($part));
            }
        }

        return implode(' ', $masked);
    }
}
