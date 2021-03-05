<?php

namespace App\Planb\SfntestBundle\Message;

class LowStockNotitication
{
    /**
     * @var string 
     */
    private $content;

    /**
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }
    
    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}

