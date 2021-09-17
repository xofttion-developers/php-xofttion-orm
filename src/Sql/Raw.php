<?php

namespace Xofttion\ORM\Sql;

use Illuminate\Database\Eloquent\Builder;

class Raw extends Condition
{

    // Constructor de la clase Raw

    /**
     * 
     * @param string $sentence
     */
    public function __construct(string $sentence)
    {
        parent::__construct($sentence, null, null, false, false);
    }

    // Métodos sobrescritos de la clase Condition

    public function flush(Builder $builder): void
    {
        $builder->whereRaw($this->getColumn());
    }
}
