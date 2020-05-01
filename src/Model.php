<?php

namespace Xofttion\ORM;

use ReflectionClass;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Xofttion\Kernel\Contracts\IDataDictionary;
use Xofttion\Kernel\Utils\Reflection;
use Xofttion\Kernel\Structs\DataDictionary;

use Xofttion\ORM\Contracts\IModel;
use Xofttion\ORM\Contracts\IModelORM;
use Xofttion\ORM\Contracts\IModelMapper;
use Xofttion\ORM\Contracts\IAggregations;
use Xofttion\ORM\Utils\ModelMapper;
use Xofttion\ORM\Utils\Builder;

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
    protected $relationships = [];
    
    /**
     *
     * @var IDataDictionary 
     */
    protected $aggregations;
    
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
    
    public function mapArray(array $data): void {
        $this->getMapper()->ofArray($this, $data);
    }

    public function register(array $data): void {
        $this->mapArray($data); $this->save();
    }

    public function rows(array $columns = ["*"]): Collection {
        return $this->select($columns)->get();
    }
    
    public function catalog(?array $aggregations = null): Collection {
        return $this->with($this->getRelationshipsEloquent($aggregations))->get();
    }

    public function find(int $id, array $columns = ["*"]): ?IModelORM {
        return $this->select($columns)->where($this->getPrimaryKey(), $id)->first();
    }

    public function record(int $id, ?array $aggregations = null): ?IModelORM {
        return $this->where($this->getPrimaryKey(), $id)->with($this->getRelationshipsEloquent($aggregations))->first();
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
    
    public function attachAggregation(string $key, $value): void {
        if (is_null($this->aggregations)) {
            $this->aggregations = new DataDictionary();
        }
        
        if (!is_null($value)) {
            $this->aggregations->attach($key, $value); // Asignando
        }
    }
    
    public function getAggregation(string $key) {
        return (is_null($this->aggregations)) ? null : $this->aggregations->getValue($key);
    }
    
    public function detachAggregation(string $key): void {
        if (!is_null($this->aggregations)) {
            $this->aggregations->detach($key); // Removiendo
        }
    }
    
    public function cleanAggregations(): void {
        if (!is_null($this->aggregations)) {
            $this->aggregations->clear(); // Limpiando
        }
    }
    
    public function getAggregations(): IAggregations {
        $aggregations = new Aggregations(); // Listando agregaciones del modelo
        
        if (!is_null($this->aggregations)) {
            $reflection = new ReflectionClass($this); // Reflexión
            
            foreach ($this->aggregations->values() as $key => $model) {
                $relation = Reflection::obtainMethod($this, $key, $reflection);

                if (!is_null($relation)) {
                    $aggregation = new Aggregation($relation, $model);
                    
                    if ($relation instanceof HasOneOrMany) {
                        $aggregations->attachChildren($key, $aggregation);
                    }
                    else if ($relation instanceof BelongsTo) {
                        $aggregations->attachParent($key, $aggregation);
                    }
                }
            }
        }
        
        return $aggregations; // Retornando agregaciones definidas del modelo
    }
    
    public function getRelationships(): array {
        return $this->relationships;
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
     * @param array $relationships
     * @return array
     */
    private function getRelationshipsEloquent(?array $relationships): array {
        return $this->getMapper()->getRelationshipsFormat(is_null($relationships) ? $this->getRelationships() : $relationships);
    }
}