<?php

namespace Xofttion\ORM\Contracts;

interface IModelMapper {
    
    // Métodos de la interfaz IModelMapper
    
    /**
     * 
     * @param IModel $model
     * @param array|null $data
     * @return IModel|null
     */
    public function ofArray(IModel $model, ?array $data): ?IModel;
    
    /**
     * 
     * @param array $data
     * @param array $conversions
     * @return array
     */
    public function getDataFormat(array $data, array $conversions): array;
    
    /**
     * 
     * @param array $aggregations
     * @return array
     */
    public function getAggregationsFormat(array $aggregations): array;
}