<?php

namespace Xofttion\ORM;

use Xofttion\Kernel\Contracts\IDataDictionary;
use Xofttion\Kernel\Structs\DataDictionary;

use Xofttion\ORM\Contracts\IAggregations;
use Xofttion\ORM\Contracts\IAggregation;

class Aggregations implements IAggregations {
    
    // Atributos de la clase Aggregations
    
    /**
     *
     * @var IDataDictionary 
     */
    private $parents;
    
    /**
     *
     * @var IDataDictionary 
     */
    private $childrens;
    
    // Constructor de la clase Aggregations
    
    public function __construct() {
        $this->parents   = new DataDictionary();
        $this->childrens = new DataDictionary();
    }
    
    // Métodos sobrescritos de la interfaz IAggregations
    
    public function attachParent(string $key, IAggregation $aggregation): void {
        $this->parents->attach($key, $aggregation);
    }
    
    public function getParents(): IDataDictionary {
        return $this->parents;
    }
    
    public function attachChildren(string $key, IAggregation $aggregation): void {
        $this->childrens->attach($key, $aggregation);
    }
    
    public function getChildrens(): IDataDictionary {
        return $this->childrens;
    }
}