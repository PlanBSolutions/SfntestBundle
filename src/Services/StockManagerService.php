<?php

namespace Planb\SfntestBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Planb\SfntestBundle\Entity\StockManager;
use Planb\SfntestBundle\Message\LowStockNotitication;

class StockManagerService
{
    /**
     * @var ContainerInterface
     */
    private $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * Processing stock insert or update
     * 
     * @param StockManager $stockItem
     * @return string
     */
    public function processStock($stockItem)
    {
        $objMan     = $this->container->get('doctrine')->getManager();
        $dublicate  = $objMan->getRepository(StockManager::class)->findDublicate($stockItem);
        
        if ($dublicate && $dublicate->getId()) {
            /* Update if dublicate */
            $dublicate->setStock($stockItem->getStock());
            $objMan->persist($dublicate);
            
            $successMessage = 'Updated Stock';
        }
        else {
            $objMan->persist($stockItem);
            
            $successMessage = 'Added Stock';
        }
        
        if ($stockItem->getStock() <= 0) {
            /* Low stock warning message */
            $this->container->get('messenger.default_bus')->dispatch(
                new LowStockNotitication('SKU '.$stockItem->getSKU().' run out of stock in the '.$stockItem->getBranch().' branch.')
            );
        }
        
        $objMan->flush();
        
        return $successMessage;
    }
}
