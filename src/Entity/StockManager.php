<?php

namespace Planb\SfntestBundle\Entity;

use Planb\SfntestBundle\Repository\StockManagerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StockManagerRepository::class)
 * @ORM\Table(name="stock_manager",uniqueConstraints={
 *     @ORM\UniqueConstraint(name="idx_sku_branch", columns={"sku", "branch"})})
 */
class StockManager
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint", columnDefinition="BIGINT(9) UNSIGNED ZEROFILL NOT NULL")
     */
    private $SKU;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $Branch;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $Stock;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSKU(): ?string
    {
        return $this->SKU;
    }

    public function setSKU(string $SKU): self
    {
        $this->SKU = $SKU;

        return $this;
    }

    public function getBranch(): ?string
    {
        return $this->Branch;
    }

    public function setBranch(string $Branch): self
    {
        $this->Branch = $Branch;

        return $this;
    }

    public function getStock(): ?string
    {
        return $this->Stock;
    }

    public function setStock(string $Stock): self
    {
        $this->Stock = $Stock;

        return $this;
    }
    
    /**
     * Magic setter method
     * 
     * @param string $key
     * @param mixed $value
     * @return StockManager
     */
    public function set(string $key, $value): self
    {
        if ($key !== 'SKU') {
            $key = ucfirst(strtolower($key));
        }
        
        if (property_exists($this, $key)) {
            $this->{$key} = $value;
        }
        
        return $this;
    }
}
