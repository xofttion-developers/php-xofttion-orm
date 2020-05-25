<?php

namespace Xofttion\ORM;

use Xofttion\Kernel\Contracts\IDataDictionary;
use Xofttion\Kernel\Structs\DataDictionary;

use Xofttion\ORM\Contracts\IRelationships;
use Xofttion\ORM\Contracts\IRelationship;

class Relationships implements IRelationships {
    
    // Atributos de la clase Relationships
    
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
    
    // Constructor de la clase Relationships
    
    public function __construct() {
        $this->parents   = new DataDictionary();
        $this->childrens = new DataDictionary();
    }
    
    // Métodos sobrescritos de la interfaz IRelationships
    
    public function attachParent(string $key, IRelationship $relationship): void {
        $this->parents->attach($key, $relationship);
    }
    
    public function getParents(): IDataDictionary {
        return $this->parents;
    }
    
    public function attachChildren(string $key, IRelationship $relationship): void {
        $this->childrens->attach($key, $relationship);
    }
    
    public function getChildrens(): IDataDictionary {
        return $this->childrens;
    }
}