<?php

namespace Jfnetwork\DoctrineEncryptedObject\EncryptionProvider;

use Jfnetwork\DoctrineEncryptedObject\EncryptionProviderInterface;
use Jfnetwork\DoctrineEncryptedObject\EncryptionWay;

use function array_map;
use function explode;
use function function_exists;
use function sodium_crypto_aead_xchacha20poly1305_ietf_decrypt;
use function sodium_crypto_aead_xchacha20poly1305_ietf_encrypt;

class Sodium implements EncryptionProviderInterface
{
    public function decrypt(mixed $value, string $key): string
    {
        [$secretKey, $nonce] = $this->getKey($key);

        return sodium_crypto_aead_xchacha20poly1305_ietf_decrypt($value, '', $nonce, $secretKey);
    }

    public function encrypt(string $value, string $key): string
    {
        [$secretKey, $nonce] = $this->getKey($key);

        return sodium_crypto_aead_xchacha20poly1305_ietf_encrypt($value, '', $nonce, $secretKey);
    }

    public function supports(EncryptionWay $encryptionWay): bool
    {
        return EncryptionWay::Sodium === $encryptionWay
            && function_exists('sodium_crypto_aead_xchacha20poly1305_ietf_encrypt');
    }

    private function getKey(string $key): array
    {
        // openssl_random_pseudo_bytes(openssl_cipher_iv_length($encryptionMethod))
        static $keys = [];
        $keys[$key] ??= array_map(
            'base64_decode',
            explode(',', $key),
        );

        return $keys[$key];
    }
}
