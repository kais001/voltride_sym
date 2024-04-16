<?php

namespace App\Security;

class CustomPasswordEncoder
{
    private const SECRET_KEY = '0123456789abcdef';

    public function encodePassword(string $raw, ?string $salt): string
    {
        return $this->encrypt($raw);
    }

    public function isPasswordValid(string $encoded, string $raw, ?string $salt): bool
    {
        return self::decrypt($encoded) === $raw;
    }

    private function encrypt(string $password): string
    {
        $cipher = openssl_encrypt($password, 'aes-128-ecb', self::SECRET_KEY, OPENSSL_RAW_DATA);
        return base64_encode($cipher);
    }

    public static function decrypt(string $encryptedPassword): string
    {
        $cipher = base64_decode($encryptedPassword);
        return openssl_decrypt($cipher, 'aes-128-ecb', self::SECRET_KEY, OPENSSL_RAW_DATA);
    }
}

