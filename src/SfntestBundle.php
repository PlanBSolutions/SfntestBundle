<?php

namespace Planb\SfntestBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SfntestBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
