<?php

namespace Xofttion\ORM\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface IStorage
{

    // Métodos de la interfaz IStorage

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
     * @param IModel $model
     * @param array|null $hidrations
     * @return IModel
     */
    public function insert(IModel $model, ?array $hidrations = null): IModel;

    /**
     * 
     * @return Collection
     */
    public function findAll(): Collection;

    /**
     * 
     * @param int $id
     * @return IModel|null
     */
    public function find(int $id): ?IModel;

    /**
     * 
     * @param array|null $aggregations
     * @return Collection
     */
    public function fetchAll(?array $aggregations = null): Collection;

    /**
     * 
     * @param int $id
     * @param array|null $aggregations
     * @return IModel|null
     */
    public function fetch(int $id, ?array $aggregations = null): ?IModel;

    /**
     * 
     * @return Collection
     */
    public function resources(): Collection;

    /**
     * 
     * @param int $id
     * @param array $data
     * @return void
     */
    public function update(int $id, array $data): void;

    /**
     * 
     * @param IModel $model
     * @return void
     */
    public function safeguard(IModel $model): void;

    /**
     * 
     * @param IModel $model
     * @return void
     */
    public function delete(IModel $model): void;
}
