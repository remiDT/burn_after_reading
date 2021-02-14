<?php


namespace App\Service;


use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class LinkGenerator
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function genererate(string $token, string $key): string
    {
        return $this->router->generate(
            'main_show',
            ['token' => $token, 'key' => $key],
            UrlGeneratorInterface::ABSOLUTE_URL);
    }


//
}
