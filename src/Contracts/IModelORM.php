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
     * @param array|null $aggregations 
     * @return Collection
     */
    public function catalog(?array $aggregations = null): Collection;

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
     * @param array|null $aggregations
     * @return IModelORM|null
     */
    public function record(int $id, ?array $aggregations = null): ?IModelORM;
    
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
     * @return array
     */
    public function getAggregations(): array;
    
    /**
     * 
     * @return array
     */
    public function getModifiables(): array;
    
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