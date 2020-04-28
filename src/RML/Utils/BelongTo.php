<?php

namespace Xofttion\ORM\RML\Utils;

class BelongTo extends Aggregation {
    
    // Constructor de la clase BelongTo
    
    /**
     * 
     * @param string $class
     * @param string $column
     * @param bool $mappable
     */
    public function __construct(string $class, string $column, bool $mappable = true) {
        parent::__construct($class, false, false, false, false, $column, $mappable);
    }
}