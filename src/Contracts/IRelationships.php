<?php

namespace Xofttion\ORM\Contracts;

use Xofttion\Kernel\Contracts\IDataDictionary;

interface IRelationships {
    
    // Métodos de la interfaz IRelationships
    
    /**
     * 
     * @param string $key
     * @param IRelationship $relationship
     * @return void
     */
    public function attachParent(string $key, IRelationship $relationship): void;

    /**
     * 
     * @return IDataDictionary
     */
    public function getParents(): IDataDictionary;
    
    /**
     * 
     * @param string $key
     * @param IRelationship $relationship
     * @return void
     */
    public function attachChildren(string $key, IRelationship $relationship): void;
    
    /**
     * 
     * @return IDataDictionary
     */
    public function getChildrens(): IDataDictionary;
}