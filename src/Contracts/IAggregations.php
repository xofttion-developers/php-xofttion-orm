<?php

namespace Xofttion\ORM\Contracts;

use Xofttion\Kernel\Contracts\IDataDictionary;

interface IAggregations {
    
    // Métodos de la interfaz IAggregations
    
    /**
     * 
     * @param string $key
     * @param IAggregation $aggregation
     * @return void
     */
    public function attachParent(string $key, IAggregation $aggregation): void;

    /**
     * 
     * @return IDataDictionary
     */
    public function getParents(): IDataDictionary;
    
    /**
     * 
     * @param string $key
     * @param IAggregation $aggregation
     * @return void
     */
    public function attachChildren(string $key, IAggregation $aggregation): void;
    
    /**
     * 
     * @return IDataDictionary
     */
    public function getChildrens(): IDataDictionary;
}