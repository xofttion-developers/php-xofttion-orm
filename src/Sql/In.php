<?php

namespace Xofttion\ORM\Sql;

use Illuminate\Database\Eloquent\Builder;

class In extends Condition {
    
    // Constructor de la clase In
    
    /**
     * 
     * @param string $column
     * @param array $value
     * @param bool $or
     * @param bool $not
     */
    public function __construct(string $column, array $value, bool $or = false, bool $not = false) {
        parent::__construct($column, null, $value, $or, $not);
    }
    
    // Métodos sobrescritos de la clase Condition
    
    public function flush(Builder $builder): void {
        $builder->whereIn(
            $this->getColumn(),     // Columna de la operación
            $this->getValue(),      // Valor de condición
            $this->getValueOr(),    // Operador de concadenación
            $this->isNot()          // Consulta esta en negación
        );
    }
}