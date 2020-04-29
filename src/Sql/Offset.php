<?php

namespace Xofttion\ORM\DAL\Sql;

use Illuminate\Database\Eloquent\Builder;

use Xofttion\ORM\DAL\Contracts\IClause;

class Offset implements IClause {
    
    // Atributos de la clase Offset
    
    /**
     *
     * @var int 
     */
    private $value;
    
    // Constructor de la clase Offset
    
    /**
     * 
     * @param int $value
     */
    public function __construct(int $value) {
        $this->setValue($value);
    }
    
    // Métodos de la clase Offset
    
    /**
     * 
     * @param int $value
     * @return void
     */
    public function setValue(int $value): void {
        $this->value = $value;
    }
    
    /**
     * 
     * @return int
     */
    public function getValue() {
        return $this->value;
    }

    // Métodos sobrescritos de la interfaz IClause
    
    public function flush(Builder $builder): void {
        $builder->offset($this->getValue());
    }
}