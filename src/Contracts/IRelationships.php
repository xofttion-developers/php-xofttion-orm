<?php

namespace Xofttion\ORM\Contracts;

use Xofttion\Kernel\Contracts\IJson;

interface IRelationships
{

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
     * @return IJson
     */
    public function getParents(): IJson;

    /**
     * 
     * @param string $key
     * @param IRelationship $relationship
     * @return void
     */
    public function attachChildren(string $key, IRelationship $relationship): void;

    /**
     * 
     * @return IJson
     */
    public function getChildrens(): IJson;
}
