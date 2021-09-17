<?php

namespace Xofttion\ORM\Sql;

use Illuminate\Database\Eloquent\Builder;

class Between extends Condition
{

    // Constructor de la clase Between

    /**
     * 
     * @param string $column
     * @param array $value
     * @param bool $or
     * @param bool $not
     */
    public function __construct(string $column, array $value, bool $or = false, bool $not = false)
    {
        parent::__construct($column, null, $value, $or, $not);
    }

    // Métodos sobrescritos de la clase Condition

    public function flush(Builder $builder): void
    {
        $builder->whereBetween($this->getColumn(), $this->getValue(), $this->getValueOr(), $this->isNot());
    }
}
