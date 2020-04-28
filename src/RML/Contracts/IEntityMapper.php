<?php

namespace Xofttion\ORM\RML\Contracts;

interface IEntityMapper {
    
    // Métodos de la interfaz IEntityMapper

    /**
     * 
     * @param IEntity $entity
     * @param array $source
     * @return IEntity|null
     */
    public function ofArray(IEntity $entity, ?array $source): ?IEntity;
    
    /**
     * 
     * @return IEntityMapper
     */
    public function clean(): IEntityMapper;
    
    /**
     * 
     * @return array
     */
    public function getEntities(): array;
}