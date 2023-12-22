<?php

namespace Jfnetwork\DoctrineEncryptedObject\EncryptionProvider;

use Jfnetwork\DoctrineEncryptedObject\EncryptionProviderInterface;
use Jfnetwork\DoctrineEncryptedObject\EncryptionWay;

use function array_map;
use function base64_encode;
use function explode;
use function function_exists;
use function random_bytes;
use function sodium_crypto_aead_xchacha20poly1305_ietf_decrypt;
use function sodium_crypto_aead_xchacha20poly1305_ietf_encrypt;
use function sprintf;

use const SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES;

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

    public function createKey(): string
    {
        return sprintf(
            '%s:%s',
            base64_encode(sodium_crypto_aead_xchacha20poly1305_ietf_keygen()),
            base64_encode(random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES)),
        );
    }

    private function getKey(string $key): array
    {
        static $keys = [];
        $keys[$key] ??= array_map(
            'base64_decode',
            explode(':', $key),
        );

        return $keys[$key];
    }
}
