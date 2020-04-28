<?php

namespace Xofttion\ORM\RML\Utils;

class ComposedBy extends Aggregation {
    
    // Constructor de la clase ComposedBy
    
    /**
     * 
     * @param string $class
     * @param bool $mappable
     */
    public function __construct(string $class, bool $mappable = true) {
        parent::__construct($class, false, false, true, false, null, $mappable);
    }
}