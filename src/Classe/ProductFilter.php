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
     * @var bool
     */
    private $fresh;

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

    public function isFresh() :bool {
        return $this->fresh;
    }

    public function setFresh(bool $fresh): self {
        $this->fresh = $fresh;
        return $this;
    }
    

}