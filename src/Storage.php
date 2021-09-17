<?php

namespace Xofttion\ORM;

use Illuminate\Database\Eloquent\Collection;
use Xofttion\ORM\Contracts\IModel;
use Xofttion\ORM\Contracts\IQuery;
use Xofttion\ORM\Contracts\IStorage;
use Xofttion\ORM\Query;

class Storage implements IStorage
{

    // Atributos de la clase Storage

    /**
     *
     * @var string 
     */
    protected $model;

    /**
     *
     * @var string 
     */
    private $context;

    // Constructor de la clase Storage

    /**
     * 
     * @param string $classModel
     */
    public function __construct(string $classModel)
    {
        $this->model = $classModel;
    }

    // Métodos sobrescritos de la interfaz IStorage

    public function setContext(?string $context): void
    {
        if (!is_null($context)) {
            $this->context = $context;
        }
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function insert(IModel $model, ?array $hidrations = null): IModel
    {
        $model->save();
        return $model->fresh($hidrations);
    }

    public function find(int $id): ?IModel
    {
        return $this->getQuery()->find($id);
    }

    public function findAll(): Collection
    {
        return $this->getQuery()->rows();
    }

    public function fetch(int $id, ?array $aggregations = null): ?IModel
    {
        return $this->getQuery()->record($id, $aggregations);
    }

    public function fetchAll(?array $aggregations = null): Collection
    {
        return $this->getQuery()->catalog($aggregations);
    }

    public function resources(): Collection
    {
        return $this->fetchAll();
    }

    public function update(int $id, array $data): void
    {
        $this->getQuery()->update($id, $data);
    }

    public function safeguard(IModel $model): void
    {
        $model->save();
        $model->refresh();
    }

    public function delete(IModel $model): void
    {
        $model->delete();
    }

    // Métodos de la clase Storage

    /**
     * 
     * @param string $model
     * @return IQuery
     */
    protected function getQuery(): IQuery
    {
        $query = new Query($this->model);

        $query->setContext($this->getContext());

        return $query;
    }
}
