<?php

namespace Xofttion\ORM\RML\Utils;

class ContainTo extends Aggregation {
    
    // Constructor de la clase ContainTo
    
    /**
     * 
     * @param string $class
     * @param bool $mappable
     */
    public function __construct(string $class, bool $mappable = true) {
        parent::__construct($class, false, false, false, false, null, $mappable);
    }
}