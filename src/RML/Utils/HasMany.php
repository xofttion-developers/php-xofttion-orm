<?php

namespace Xofttion\ORM\RML\Utils;

class HasMany extends Aggregation {
    
    // Constructor de la clase HasMay
    
    /**
     * 
     * @param string $class
     * @param bool $mappable
     */
    public function __construct(string $class, bool $mappable = true) {
        parent::__construct($class, true, true, false, false, null, $mappable);
    }
}