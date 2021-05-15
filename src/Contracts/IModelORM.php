<?php

namespace Xofttion\ORM\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface IModelORM {
    
    // Métodos de la interfaz IModelORM
    
    /**
     * 
     * @param string|null $context
     * @return void
     */
    public function setContext(?string $context): void;
    
    /**
     * 
     * @return string
     */
    public function getPrimaryKey(): string;
    
    /**
     * 
     * @param array $data
     * @return void
     */
    public function mapArray(array $data): void;
    
    /**
     * 
     * @param array $data
     * @return void
     */
    public function setData(array $data): void;

    /**
     *
     * @param array $data
     */
    public function register(array $data);

    /**
     * 
     * @param array $columns 
     * @return Collection
     */
    public function rows(array $columns = ["*"]): Collection;

    /**
     * 
     * @param array|null $references 
     * @return Collection
     */
    public function catalog(?array $references = null): Collection;

    /**
     * 
     * @param int $id
     * @param array $columns
     * @return IModelORM|null
     */
    public function find(int $id, array $columns = ["*"]): ?IModelORM;

    /**
     * 
     * @param int $id
     * @param array|null $references
     * @return IModelORM|null
     */
    public function record(int $id, ?array $references = null): ?IModelORM;
    
    /**
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function modify(int $id, array $data): bool;
    
    /**
     * 
     * @param int $id
     * @return bool
     */
    public function remove(int $id): bool;

    /**
     * 
     * @return array
     */
    public function getConversions(): array;
    
    /**
     * 
     * @param string $key
     * @param object $value
     * @return void
     */
    public function attachRelationship(string $key, $value): void;
    
    /**
     * 
     * @param string $key
     * @return object
     */
    public function getRelationship(string $key);
    
    /**
     * 
     * @param string $key
     * @return void
     */
    public function detachRelationship(string $key): void;
    
    /**
     * 
     * @return void
     */
    public function cleanRelationships(): void;

    /**
     * 
     * @return IRelationships|null
     */
    public function getRelationships(): ?IRelationships;
    
    /**
     * 
     * @return array
     */
    public function getReferences(): array;
    
    /**
     * 
     * @return array
     */
    public function getUnchangeables(): array;
    
    /**
     * 
     * @return array
     */
    public function getNulleables(): array;
    
    /**
     * 
     * @return array
     */
    public function toArray();

    /**
     * 
     * @return Builder
     */
    public function newBuilder(): Builder;
    
    /**
     * 
     * @return IModelMapper
     */
    public function getMapper(): IModelMapper;
}