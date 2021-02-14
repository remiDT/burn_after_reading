<?php


namespace App\Service\Crypto;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CryptoService
{

    private ParameterBagInterface $bag;

    public function __construct(ParameterBagInterface $bag)
    {
        $this->bag = $bag;
    }

    public function encrypted(string $message): array
    {
        $randomKey = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
        $ciphertext = sodium_crypto_secretbox($message, hex2bin($this->bag->get('nonce')), $randomKey);
        return [bin2hex($randomKey), bin2hex($ciphertext)];
    }


    public function decrypted(string $encrypted, string $key): string
    {
        return sodium_crypto_secretbox_open(hex2bin($encrypted), hex2bin($this->bag->get('nonce')), hex2bin($key));
    }

    public function isValidKey(string $encrypted, string $key): bool
    {
        return sodium_crypto_secretbox_open(hex2bin($encrypted), hex2bin($this->bag->get('nonce')), hex2bin($key)) !== false;
    }

}
