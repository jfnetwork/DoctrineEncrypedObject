<?php

namespace Jfnetwork\DoctrineEncryptedObject\EncryptionProvider;

use Jfnetwork\DoctrineEncryptedObject\EncryptionProviderInterface;
use Jfnetwork\DoctrineEncryptedObject\EncryptionWay;

use function array_map;
use function explode;
use function function_exists;
use function openssl_cipher_iv_length;
use function openssl_decrypt;
use function openssl_encrypt;
use function openssl_random_pseudo_bytes;
use function sprintf;

class Openssl implements EncryptionProviderInterface
{
    private const ENCRYPTION_METHOD = 'AES-256-CBC';

    public function decrypt(mixed $value, string $key): string
    {
        [$secretKey, $iv] = $this->getKey($key);

        return openssl_decrypt($value, self::ENCRYPTION_METHOD, $secretKey, 0, $iv,);
    }

    public function encrypt(string $value, string $key): string
    {
        [$secretKey, $iv] = $this->getKey($key);

        return openssl_encrypt($value, self::ENCRYPTION_METHOD, $secretKey, 0, $iv);
    }

    public function supports(EncryptionWay $encryptionWay): bool
    {
        return EncryptionWay::Openssl === $encryptionWay && function_exists('openssl_encrypt');
    }

    public function createKey(): string
    {
        return sprintf(
            '%s:%s',
            base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::ENCRYPTION_METHOD))),
            base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::ENCRYPTION_METHOD))),
        );
    }

    private function getKey(string $key): array
    {
        //
        static $keys = [];
        $keys[$key] ??= array_map(
            'base64_decode',
            explode(':', $key),
        );

        return $keys[$key];
    }
}
