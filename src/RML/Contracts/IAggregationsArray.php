<?php

namespace Xofttion\ORM\RML\Contracts;

interface IAggregationsArray {
    
    // Métodos de la interfaz IAggregationsArray
    
    /**
     * 
     * @param IEntity $entity
     * @return array
     */
    public function ofEntity(IEntity $entity): array;
}