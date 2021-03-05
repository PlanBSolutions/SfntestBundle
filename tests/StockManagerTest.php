<?php

namespace Planb\SfntestBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Planb\SfntestBundle\Entity\StockManager;
use Planb\SfntestBundle\Repository\StockManagerRepository;
use Planb\SfntestBundle\Services\StockManagerService;

class StockManagerTest extends TestCase
{
    public function testAddStockEntity(): void
    {
        $sampleData = [
            'SKU'       => '123456789',
            'Branch'    => 'ABC',
            'Stock'     => 1.00
        ];
        
        $successMessage = $this->processStockData($sampleData);
        
        $this->assertStringContainsString('Added Stock', $successMessage);
    }
    
    public function testUpdateStockEntity(): void
    {
        $sampleData = [
            'SKU'       => '123456789',
            'Branch'    => 'ABC',
            'Stock'     => 2.00
        ];
        
        $successMessage = $this->processStockData($sampleData);
        
        $this->assertStringContainsString('Updated Stock', $successMessage);
    }
    
    public function testInvalidStockEntity(): void
    {
        $error      = false;
        $sampleData = [
            'SKU'       => '000000000',
            'Branch'    => '', /* Branch missing */
            'Stock'     => 1.00
        ];
        
        try {
            $this->processStockData($sampleData);
        }
        catch (NotNullConstraintViolationException $ex) {
            $error = $ex;
        }
        catch (\Exception $e) {
            $error = $e;
        }
        
        $this->assertTrue($error !== false);
    }
    
    /**
     * Creating the stockitem and saving it to the database
     * 
     * @param array $sampleData
     * @return string - Success message passed from StockManagerService->processStock()
     */
    private function processStockData(array $sampleData)
    {
        $service    = new StockManagerService(new ContainerInterface());
        $stockItem  = new StockManager();
        
        $stockItem->setSKU($sampleData['SKU']);
        $stockItem->setBranch($sampleData['Branch']);
        $stockItem->setStock($sampleData['Stock']);
        
        return $service->processStock($stockItem);
    }
}
