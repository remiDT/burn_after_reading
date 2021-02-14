<?php


namespace App\Service;


use App\NotExistKeyException;
use App\Service\Crypto\CryptoService;
use Predis\Client;
use Symfony\Component\Filesystem\Filesystem;

//todo check if save is successful
class SecretService
{
    private Client $redisClient;
    private TokenGenerator $generator;
    private Filesystem $filesystem;
    private CryptoService $cryptoService;

    public function __construct(Client $redisClient, TokenGenerator $generator, Filesystem $filesystem, CryptoService $cryptoService)
    {
        $this->redisClient = $redisClient;
        $this->generator = $generator;
        $this->filesystem = $filesystem;
        $this->cryptoService = $cryptoService;
    }

    public function isValid(string $key): string
    {
        if (!$this->redisClient->exists($key)) {
            throw new NotExistKeyException('ce document n\'existe plus');
        }

        return true;
    }

    public function save(string $message, int $ttl): array
    {
        list($key, $cipherMessage) = $this->cryptoService->encrypted($message);
        $token = $this->generator->generate();
        $this->redisClient->set($token, $cipherMessage);
        $this->redisClient->expire($token, $ttl);
        return [$key, $token];
    }

    public function getAndDelete(string $token, string $key): string
    {
        $encrypted = $this->redisClient->get($token);
        if (!$encrypted) {
            throw new NotExistKeyException('ce document n\'existe plus');
        }
        $this->redisClient->del([$token]);
        return $this->cryptoService->decrypted($encrypted, $key);
    }

    public function checkValid(string $token, string $key): string
    {
        $encrypted = $this->redisClient->get($token);
        if (!$encrypted) {
            throw new NotExistKeyException('ce document n\'existe plus');
        }
        return $this->cryptoService->isValidKey($encrypted, $key);
    }

}
