<?php

namespace Xofttion\ORM\RML\Contracts;

interface IRepository {
    
    // Métodos de la interfaz IRepository
    
    /**
     * 
     * @param string|null $context
     * @return void
     */
    public function setContext(?string $context): void;
    
    /**
     * 
     * @return string|null
     */
    public function getContext(): ?string;

    /**
     * 
     * @return IEntity|null
     */
    public function getEntity(): ?IEntity;

    /**
     * 
     * @param IEntity $entity
     * @return void
     */
    public function insert(IEntity $entity): void;
    
    /**
     * 
     * @return IEntityCollection
     */
    public function findAll(): IEntityCollection;

    /**
     * 
     * @param int $id
     * @return IEntity|null
     */
    public function find(int $id): ?IEntity;
    
    /**
     * 
     * @param array|null $aggregations
     * @return IEntityCollection
     */
    public function fetchAll(?array $aggregations = null): IEntityCollection;

    /**
     * 
     * @param int $id
     * @param array|null $aggregations
     * @return IEntity|null
     */
    public function fetch(int $id, ?array $aggregations = null): ?IEntity;
    
    /**
     * 
     * @return IEntityCollection
     */
    public function resources(): IEntityCollection;
    
    /**
     * 
     * @param int $id
     * @param array $data
     * @return void
     */
    public function update(int $id, array $data): void;
    
    /**
     * 
     * @param IEntity $entity
     * @return void
     */
    public function save(IEntity $entity): void;
    
    /**
     * 
     * @param IEntity $entity
     * @return void
     */
    public function delete(IEntity $entity): void;
}