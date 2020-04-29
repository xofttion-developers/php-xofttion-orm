<?php

namespace Xofttion\ORM\Sql;

use Illuminate\Database\Eloquent\Builder;

use Xofttion\ORM\Contracts\IClause;

class OrderBy implements IClause {
    
    // Atributos de la clase OrderBy
    
    /**
     *
     * @var string 
     */
    private $column;
    
    /**
     *
     * @var bool 
     */
    private $asc;
    
    // Constructor de la clase OrderBy
    
    /**
     * 
     * @param string $column
     * @param bool $asc
     */
    public function __construct(string $column, bool $asc = true) {
        $this->setColumn($column); $this->setAsc($asc);
    }
    
    // Métodos de la clase OrderBy
    
    /**
     * 
     * @param string $column
     * @return void
     */
    public function setColumn(string $column): void {
        $this->column = $column;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getColumn(): ?string {
        return $this->column;
    }
    
    /**
     * 
     * @param bool $asc
     * @return void
     */
    public function setAsc(bool $asc): void {
        $this->asc = $asc;
    }
    
    /**
     * 
     * @return bool|null
     */
    public function isAsc(): ?bool {
        return $this->asc;
    }
    
    /**
     * 
     * @return string
     */
    private function getDirection(): string {
        return ($this->isAsc()) ? "asc" : "desc";
    }

    // Métodos sobrescritos de la interfaz IClause
    
    public function flush(Builder $builder): void {
        $builder->orderBy($this->getColumn(), $this->getDirection());
    }
}