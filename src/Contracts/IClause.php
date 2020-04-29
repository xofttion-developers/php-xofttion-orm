<?php

namespace Xofttion\ORM\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface IClause {
    
    /**
     * 
     * @param Builder $builder
     * @return void
     */
    public function flush(Builder $builder): void;
}