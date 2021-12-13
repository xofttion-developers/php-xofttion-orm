<?php

namespace Xofttion\ORM\Utils;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class Builder extends EloquentBuilder
{

    // Métodos de la clase Builder
    
    /**
     * 
     * @param string $table
     * @return void
     */
    public function setTable(string $table): void
    {
        $this->query->from($table);
    }
}
