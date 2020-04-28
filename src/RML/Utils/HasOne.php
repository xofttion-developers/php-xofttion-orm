<?php

namespace Xofttion\ORM\RML\Utils;

class HasOne extends Aggregation {
    
    // Constructor de la clase HasOne
    
    /**
     * 
     * @param string $class
     * @param bool $mappable
     */
    public function __construct(string $class, bool $mappable = true) {
        parent::__construct($class, false, true, false, false, null, $mappable);
    }
}