<?php

namespace Planb\SfntestBundle\MessageHandler;

use Planb\SfntestBundle\Message\LowStockNotitication;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class LowStockNotiticationHandler implements MessageHandlerInterface
{
    /**
     * @var MailerInterface 
     */
    private $mailer;
    
    /**
     * @param MailerInterface $mailer - for email transport
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    
    /**
     * @param LowStockNotitication $message
     */
    public function __invoke(LowStockNotitication $message)
    {
        $email = new Email();
        
        $email->subject('Low Stock Warning')->text($message->getContent());
        
        $this->mailer->send($email);
    }
}

