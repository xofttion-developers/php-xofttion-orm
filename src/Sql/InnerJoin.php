<?php

namespace Xofttion\ORM\Sql;

use Illuminate\Database\Eloquent\Builder;
use Xofttion\ORM\Contracts\IClause;

class InnerJoin implements IClause
{

    // Atributos de la clase InnerJoin

    /**
     *
     * @var string 
     */
    private $relation;

    /**
     *
     * @var string 
     */
    private $relation_column;

    /**
     *
     * @var string 
     */
    private $parent_column;

    /**
     *
     * @var string 
     */
    private $operator;

    // Constructor de la clase InnerJoin

    /**
     * 
     * @param string $relation
     * @param string $relationColumn
     * @param string $parentColumn
     * @param string $operator
     */
    public function __construct(string $relation, string $relationColumn, string $parentColumn, string $operator = "=")
    {
        $this->setRelation($relation);
        $this->setRelationColumn($relationColumn);
        $this->setParentColumn($parentColumn);
        $this->setOperator($operator);
    }

    // Métodos de la clase InnerJoin

    /**
     * 
     * @param string $relation
     * @return void
     */
    public function setRelation(string $relation): void
    {
        $this->relation = $relation;
    }

    /**
     * 
     * @return string
     */
    public function getRelation(): string
    {
        return $this->relation;
    }

    /**
     * 
     * @param string $relationColumn
     * @return void
     */
    public function setRelationColumn(string $relationColumn): void
    {
        $this->relation_column = $relationColumn;
    }

    /**
     * 
     * @return string
     */
    public function getRelationColumn(): string
    {
        return $this->relation_column;
    }

    /**
     * 
     * @param string $parentColumn
     * @return void
     */
    public function setParentColumn(string $parentColumn): void
    {
        $this->parent_column = $parentColumn;
    }

    /**
     * 
     * @return string
     */
    public function getParentColumn(): string
    {
        return $this->parent_column;
    }

    /**
     * 
     * @param string $operator
     * @return void
     */
    public function setOperator(string $operator): void
    {
        $this->operator = $operator;
    }

    /**
     * 
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    // Métodos sobrescritos de la interfaz IClause

    public function flush(Builder $builder): void
    {
        $builder->join($this->getRelation(), $this->getParentColumn(), $this->getOperator(), $this->getRelationColumn());
    }
}
