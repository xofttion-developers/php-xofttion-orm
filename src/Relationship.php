<?php

namespace Xofttion\ORM;

use Illuminate\Database\Eloquent\Relations\Relation;

use Xofttion\ORM\Contracts\IRelationship;

class Relationship implements IRelationship {
    
    // Atributos de la clase Relationship
    
    /**
     *
     * @var Relation 
     */
    private $relation;
    
    /**
     *
     * @var object 
     */
    private $value;
    
    // Constructor de la clase Relationship
    
    /**
     * 
     * @param Relation $relation
     * @param object $value
     */
    public function __construct(Relation $relation, $value = null) {
        $this->setRelation($relation); $this->setValue($value);
    }
    
    // Métodos sobrescritos de la interfaz IAggregation

    public function setRelation(Relation $relation): void {
        $this->relation = $relation;
    }
    
    public function getRelation(): ?Relation {
        return $this->relation;
    }

    public function setValue($value): void {
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }
}