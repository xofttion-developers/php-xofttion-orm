<?php

namespace Xofttion\ORM\Contracts;

use Illuminate\Database\Eloquent\Model;

abstract class IModel extends Model implements IModelORM
{

    // Métodos de la clase IModel

    /**
     * 
     * @param array $relationships
     * @return void
     */
    public function reaload(array $relationships): void
    {
        $this->load($this->getMapper()->getReferencesFormat($relationships));
    }
}
