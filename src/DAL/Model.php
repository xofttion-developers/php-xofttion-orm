<?php

namespace Xofttion\ORM\DAL;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

use Xofttion\ORM\DAL\Contracts\IModel;
use Xofttion\ORM\DAL\Contracts\IModelORM;
use Xofttion\ORM\DAL\Contracts\IModelMapper;
use Xofttion\ORM\DAL\Utils\ModelMapper;
use Xofttion\ORM\DAL\Utils\Builder;

class Model extends IModel {
    
    // Atributos de clase Model

    /**
     *
     * @var array 
     */
    protected $conversionsDefault = [];

    /**
     *
     * @var array 
     */
    protected $conversions = [];
    
    /**
     *
     * @var array 
     */
    protected $aggregations = [];
    
    /**
     *
     * @var array 
     */
    protected $modifiables = [];
    
    /**
     *
     * @var array 
     */
    protected $nulleables = [];
    
    // Atributos de clase IModel
    
    /**
     *
     * @var bool 
     */
    public $timestamps = false;
    
    // Métodos de la clase Model

    /**
     * 
     * @param array $data
     * @return void
     */
    public function mapArray(array $data): void {
        $this->getMapper()->ofArray($this, $data);
    }
    
    /**
     * 
     * @param array $data
     * @return array
     */
    public function getDataFormat(array $data): array {
        return $this->getMapper()->getDataFormat($data, $this->getConversions());
    }  

    // Métodos sobrescritos de la interfaz IModel
    
    public function setContext(?string $context): void {
        if (!is_null($context)) {
            $this->setConnection($context); // Conexión
        }
    }
    
    public function getPrimaryKey(): string {
        return $this->primaryKey;
    }

    public function register(array $data): void {
        $this->setArray($data); $this->save();
    }

    public function rows(array $columns = ["*"]): Collection {
        return $this->select($columns)->get();
    }
    
    public function catalog(?array $aggregations = null): Collection {
        return $this->with($this->getEloquentAggregations($aggregations))->get();
    }

    public function find(int $id, array $columns = ["*"]): ?IModelORM {
        return $this->select($columns)->where($this->getPrimaryKey(), $id)->first();
    }

    public function record(int $id, ?array $aggregations = null): ?IModelORM {
        return $this->where($this->getPrimaryKey(), $id)->with($this->getEloquentAggregations($aggregations))->first();
    }

    public function modify(int $id, array $data): bool {
        return $this->where($this->getPrimaryKey(), $id)->update($this->getDataFormat($data));
    }

    public function remove(int $id): bool {
        return $this->where($this->getPrimaryKey(), $id)->delete();
    }

    public function getConversions(): array {
        return array_merge($this->conversionsDefault, $this->conversions);
    }
    
    public function getAggregations(): array {
        return $this->aggregations;
    }
    
    public function getModifiables(): array {
        return $this->modifiables;
    }
    
    public function getNulleables(): array {
        return $this->nulleables;
    }
    
    public function newBuilder(): EloquentBuilder {
        return $this->newQuery();
    }
    
    public function getMapper(): IModelMapper {
        return ModelMapper::getInstance();
    }
    
    // Métodos sobrescritos de la clase EloquentModel
    
    public function newEloquentBuilder($query) {
        return new Builder($query);
    }
    
    // Métodos de la clase Model
    
    /**
     * 
     * @param array $aggregations
     * @return array
     */
    private function getEloquentAggregations(?array $aggregations): array {
        return $this->getMapper()->getAggregationsFormat(is_null($aggregations) ? $this->getAggregations() : $aggregations);
    }
}