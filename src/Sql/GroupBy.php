<?php

namespace Xofttion\ORM\Sql;

use Illuminate\Database\Eloquent\Builder;
use Xofttion\ORM\Contracts\IClause;

class GroupBy implements IClause
{

    // Atributos de la clase GroupBy

    /**
     *
     * @var array 
     */
    private $columns;

    // Constructor de la clase GroupBy

    /**
     * 
     * @param array $columns
     */
    public function __construct(array $columns)
    {
        $this->setColumns($columns);
    }

    // Métodos de la clase GroupBy

    /**
     * 
     * @param array $columns
     * @return void
     */
    public function setColumns(array $columns): void
    {
        $this->columns = $columns;
    }

    /**
     * 
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    // Métodos sobrescritos de la interfaz IClause

    public function flush(Builder $builder): void
    {
        $builder->groupBy($this->getColumns());
    }
}
