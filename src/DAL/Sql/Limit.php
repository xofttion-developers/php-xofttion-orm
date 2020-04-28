<?php

namespace Xofttion\ORM\DAL\Sql;

use Illuminate\Database\Eloquent\Builder;

use Xofttion\ORM\DAL\Contracts\IClause;

class Limit implements IClause {
    
    // Atributos de la clase Limit
    
    /**
     *
     * @var int 
     */
    private $count;
    
    // Constructor de la clase Limit
    
    /**
     * 
     * @param int $count
     */
    public function __construct(int $count) {
        $this->setCount($count);
    }
    
    // Métodos de la clase Limit
    
    /**
     * 
     * @param int $count
     * @return void
     */
    public function setCount(int $count): void {
        $this->count = $count;
    }
    
    /**
     * 
     * @return int
     */
    public function getCount() {
        return $this->count;
    }

    // Métodos sobrescritos de la interfaz IClause
    
    public function flush(Builder $builder): void {
        $builder->limit($this->getCount());
    }
}