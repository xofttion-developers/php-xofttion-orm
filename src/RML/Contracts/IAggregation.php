<?php

namespace Xofttion\ORM\RML\Contracts;

interface IAggregation {
    
    // Métodos de la interfaz IAggregation
    
    /**
     * 
     * @return string|null
     */
    public function getClass(): ?string;
    
    /**
     * 
     * @return bool|null
     */
    public function isArray(): ?bool;
    
    /**
     * 
     * @return bool|null
     */
    public function isCascade(): ?bool;
    
    /**
     * 
     * @return bool|null
     */
    public function isHidration(): ?bool;
    
    /**
     * 
     * @return bool|null
     */
    public function isBelong(): ?bool;
    
    /**
     * 
     * @return string|null
     */
    public function getKey(): ?string;

    /**
     * 
     * @return bool|null
     */
    public function isMappable(): ?bool;
}