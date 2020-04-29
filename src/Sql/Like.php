<?php

namespace Xofttion\ORM\Sql;

class Like extends Condition {
    
    // Constructor de la clase Like
    
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
    
    public function getOperator(): ?string {
        return $this->isNot() ? "NOT LIKE" : "LIKE"; // Retornando operador
    }
}