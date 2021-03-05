<?php

namespace Planb\SfntestBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Filesystem\Filesystem;
use Planb\SfntestBundle\Entity\StockManager;
use Planb\SfntestBundle\Services\StockManagerService;

class ImportStockData extends Command
{
    protected static $defaultName = 'stockmanager:import-data';
    
    /**
     * @var StockManagerService 
     */
    private $service;
    
    /**
     * @param StockManagerService $service
     * @param string|null $name passed on to parent constructor
     */
    public function __construct(StockManagerService $service, string $name = null)
    {
        $this->service = $service;
        parent::__construct($name);
    }
    
    /**
     * Command config
     */
    protected function configure()
    {
        $this->setDescription('Import stock data from CSV file')
            ->setHelp('This command will import the stock data given by a csv file form a location passed as first argument')
            ->addArgument('csvFile', InputArgument::REQUIRED);
    }
    
    /**
     * Processing CSV import
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return boolean (success = 1, fail = 0)
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $csvFile    = $input->getArgument('csvFile');
        $filesystem = new Filesystem();
        
        if (empty($csvFile)) {
            $output->writeln('Parameter for CSV file missing');
            return Command::FAILURE;
        }
        elseif (!$filesystem->exists($csvFile)) {
            $output->writeln('CSV file '.$csvFile.' does not exist');
            return Command::FAILURE;
        }
        
        $csvData = file($csvFile);
        
        if (!is_array($csvData) || empty($csvData)) {
            $output->writeln('CSV file '.$csvFile.' is empty');
            return Command::FAILURE;
        }
        
        $keys       = explode(',', array_shift($csvData));
        $counter    = 0;
        $errors     = [];
        
        if ($this->csvKeysValid($keys)) {
            $output->writeln('CSV column names are invalid');
            return Command::FAILURE;
        }
        
        foreach ($csvData as $row => $data) {
            $stockItem  = new StockManager();
            $stockData  = explode(',', $data);
            
            foreach ($keys as $id => $key) {
                if (isset($stockData[$id])) {
                    $stockItem->set(trim($key), trim($stockData[$id]));
                }
            }
            
            try {
                $this->service->processStock($stockItem);
                $output->writeln('Importing row '.$counter++.': '.trim($data));
            }
            catch (\Exception $e) {
                $errors[] = 'Row '.$row.': '.$e->getMessage();
            }
        }
        
        $output->writeln('Total of '.$counter.' stock items imported successfully.');
        
        if (!empty($errors)) {
            $output->writeln(count($errors).' errors');
            $output->writeln('----------------------');
            
            foreach ($errors as $error) {
                $output->writeln($error);
            }
        }
        
        return Command::SUCCESS;
    }
    
    /**
     * Check if CSV header has correct length and all requiered fileds
     * 
     * @param array $keys
     * @return boolean
     */
    private function csvKeysValid(array $keys)
    {
        return (is_array($keys) && 
                (count($keys) == 3) &&
                (array_search('SKU', $keys) !== false) &&
                (array_search('BRANCH', $keys) !== false) &&
                (array_search('STOCK', $keys) !== false));
    }
}
