<?php

namespace Xofttion\ORM\Sql;

use Illuminate\Database\Eloquent\Builder;

use Xofttion\ORM\Contracts\IPredicate;

class Condition implements IPredicate
{

    // Atributos de la clase Condition

    /**
     *
     * @var string 
     */
    private $column;

    /**
     *
     * @var string 
     */
    private $operator;

    /**
     *
     * @var mixed 
     */
    private $value;

    /**
     *
     * @var bool 
     */
    private $or;

    /**
     *
     * @var bool 
     */
    private $not;

    // Constructor de la clase Condition

    /**
     * 
     * @param string $column
     * @param string|null $operator
     * @param mixed $value
     * @param bool $or
     * @param bool $not
     */
    public function __construct(string $column, ?string $operator, $value, bool $or = false, bool $not = false)
    {
        $this->setColumn($column);
        $this->setOperator($operator);
        $this->setValue($value);
        $this->setOr($or);
        $this->setNot($not);
    }

    // Métodos sobrescritos de la interfaz ICondition

    public function setColumn(string $column): void
    {
        $this->column = $column;
    }

    public function getColumn(): ?string
    {
        return $this->column;
    }

    public function setOperator(?string $operator): void
    {
        $this->operator = $operator;
    }

    public function getOperator(): ?string
    {
        return $this->operator;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setOr(bool $or): void
    {
        $this->or = $or;
    }

    public function isOr(): bool
    {
        return $this->or;
    }

    public function setNot(bool $not): void
    {
        $this->not = $not;
    }

    public function isNot(): bool
    {
        return $this->not;
    }

    public function flush(Builder $builder): void
    {
        $builder->where($this->getColumn(), $this->getOperator(), $this->getValue(), $this->getValueOr());
    }

    // Métodos de la clase Condition

    protected function getValueOr(): string
    {
        return $this->isOr() ? "or" : "and";
    }
}