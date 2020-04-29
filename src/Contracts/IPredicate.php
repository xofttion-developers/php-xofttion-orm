<?php

namespace Xofttion\ORM\Contracts;

interface IPredicate extends IClause {
    
    // Métodos de la interfaz IPredicate
    
    /**
     * 
     * @param string $column
     * @return void
     */
    public function setColumn(string $column): void;
    
    /**
     * 
     * @return string|null
     */
    public function getColumn(): ?string;
    
    /**
     * 
     * @param string|null $operator
     * @return void
     */
    public function setOperator(?string $operator): void;
    
    /**
     * 
     * @return string|null
     */
    public function getOperator(): ?string;
    
    /**
     * 
     * @param object $value
     * @return void
     */
    public function setValue($value): void;
    
    /**
     * 
     * @return object
     */
    public function getValue();
    
    /**
     * 
     * @param bool $or
     * @return void
     */
    public function setOr(bool $or): void;
    
    /**
     * 
     * @return bool|null
     */
    public function isOr(): bool;
    
    /**
     * 
     * @param bool $not
     * @return void
     */
    public function setNot(bool $not): void;
    
    /**
     * 
     * @return bool|null
     */
    public function isNot(): bool;
}