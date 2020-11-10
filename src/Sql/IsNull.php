<?php

namespace Xofttion\ORM\Sql;

use Illuminate\Database\Eloquent\Builder;

class IsNull extends Condition {
    
    // Constructor de la clase IsNull
    
    /**
     * 
     * @param string $column
     * @param bool $or
     * @param bool $not
     */
    public function __construct(string $column, bool $or = false, bool $not = false) {
        parent::__construct($column, null, null, $or, $not);
    }
    
    // Métodos sobrescritos de la clase Condition
    
    public function flush(Builder $builder): void {
        $builder->whereNull($this->getColumn(), $this->getValueOr(), $this->isNot());
    }
}