<?php

namespace Xofttion\ORM;

use Xofttion\Kernel\Contracts\IJson;
use Xofttion\Kernel\Structs\Json;

use Xofttion\ORM\Contracts\IRelationships;
use Xofttion\ORM\Contracts\IRelationship;

class Relationships implements IRelationships {
    
    // Atributos de la clase Relationships
    
    /**
     *
     * @var IJson 
     */
    private $parents;
    
    /**
     *
     * @var IJson 
     */
    private $childrens;
    
    // Constructor de la clase Relationships
    
    public function __construct() {
        $this->parents   = new Json();
        $this->childrens = new Json();
    }
    
    // Métodos sobrescritos de la interfaz IRelationships
    
    public function attachParent(string $key, IRelationship $relationship): void {
        $this->parents->attach($key, $relationship);
    }
    
    public function getParents(): IJson {
        return $this->parents;
    }
    
    public function attachChildren(string $key, IRelationship $relationship): void {
        $this->childrens->attach($key, $relationship);
    }
    
    public function getChildrens(): IJson {
        return $this->childrens;
    }
}