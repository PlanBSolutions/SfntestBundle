<?php

namespace App\Planb\SfntestBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PlanbSfntestBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}