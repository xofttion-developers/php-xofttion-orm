<?php

namespace Xofttion\ORM\RML\Contracts;

interface IAggregationsKeys {
    
    // Métodos de la interfaz IAggregationsKeys
    
    /**
     * 
     * @return array
     */
    public function all(): array;
    
    /**
     * 
     * @return array
     */
    public function hidration(): array;
    
    /**
     * 
     * @return array
     */
    public function cascade(): array;
    
    /**
     * 
     * @return array
     */
    public function belong(): array;
    
    /**
     * 
     * @return array
     */
    public function mappable(): array;
}