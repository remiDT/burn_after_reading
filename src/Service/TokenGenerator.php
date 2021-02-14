<?php


namespace App\Service;


class TokenGenerator
{
    public function generate(): string
    {
        return bin2hex(random_bytes(35));
    }

}
