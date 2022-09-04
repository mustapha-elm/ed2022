<?php

namespace App\Classe;

use App\Entity\Category;
use Doctrine\Common\Collections\Collection;

class ProductFilter {
    
    /**
     * @var Category[] 
     */
    private $categories;

    /**
     * @var int[] 
     */
    private $prices;

     /**
     * @var int[]
     */
    private $statements;

    public function __construct($categories = [], $prices = [], $fresh = null)
    {
        $this->categories = $categories;
        $this->prices = $prices;
        $this->fresh = $fresh;
    }
    
    public function getCategories() :array {
        return $this->categories;
    }

    public function setCategories(array $categories): self {
        $this->categories = $categories;
        return $this;
    }

    public function getPrices() :array {
        return $this->prices;
    }

    public function setPrices(array $prices): self {
        $this->prices = $prices;
        return $this;
    }

    public function getStatement()  {
        return $this->statements;
    }

    public function setStatement(int $statement): self {
        $this->statements[] = $statement;
        return $this;
    }

    public function addAllStatements() {
        $this->statements = [1, 2];
        return $this;
    }
    

}