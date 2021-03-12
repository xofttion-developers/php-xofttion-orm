<?php

namespace Xofttion\ORM;

use ReflectionClass;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Xofttion\Kernel\Contracts\IJson;
use Xofttion\Kernel\Utils\ReflectiveClass;
use Xofttion\Kernel\Structs\Json;

use Xofttion\ORM\Contracts\IModel;
use Xofttion\ORM\Contracts\IModelORM;
use Xofttion\ORM\Contracts\IModelMapper;
use Xofttion\ORM\Contracts\IRelationships;
use Xofttion\ORM\Utils\ModelMapper;
use Xofttion\ORM\Utils\Builder;

class Model extends IModel {
    
    // Atributos de clase Model
    
    /**
     * 
     * @var ReflectiveClass
     */
    private static $reflective;

    /**
     *
     * @var array 
     */
    protected $conversions = [];

    /**
     *
     * @var array 
     */
    protected $references = [];
    
    /**
     *
     * @var IJson 
     */
    protected $relationships;
    
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
    
    public function setData(array $data): void {
        foreach ($data as $key => $value) {
            $this[$key] = $value;
        }
    }

    public function register(array $data): void {
        $this->mapArray($data); $this->save();
    }

    public function rows(array $columns = ["*"]): Collection {
        return $this->select($columns)->get();
    }
    
    public function catalog(?array $references = null): Collection {
        return $this->with($this->getReferencesEloquent($references))->get();
    }

    public function find(int $id, array $columns = ["*"]): ?IModelORM {
        return $this->select($columns)->where($this->getPrimaryKey(), $id)->first();
    }

    public function record(int $id, ?array $references = null): ?IModelORM {
        return $this->where($this->getPrimaryKey(), $id)->with($this->getReferencesEloquent($references))->first();
    }

    public function modify(int $id, array $data): bool {
        return $this->where($this->getPrimaryKey(), $id)->update($this->getDataFormat($data));
    }

    public function remove(int $id): bool {
        return $this->where($this->getPrimaryKey(), $id)->delete();
    }

    public function getConversions(): array {
        return array_merge($this->getConversionsDefault(), $this->conversions);
    }
    
    public function attachRelationship(string $key, $value): void {
        if (is_null($this->relationships)) {
            $this->relationships = new Json();
        }
        
        if (!is_null($value)) {
            $this->relationships->attach($key, $value); // Asignando
        }
    }
    
    public function getRelationship(string $key) {
        return (is_null($this->relationships)) ? null : $this->relationships->getValue($key);
    }
    
    public function detachRelationship(string $key): void {
        if (!is_null($this->relationships)) {
            $this->relationships->detach($key); // Removiendo
        }
    }
    
    public function cleanRelationships(): void {
        if (!is_null($this->relationships)) {
            $this->relationships->clear(); // Limpiando
        }
    }
    
    public function getRelationships(): ?IRelationships {
        if (is_defined($this->relationships)) {
            $reflection    = new ReflectionClass($this); // Reflexión del modelo
            $relationships = new Relationships(); // Relaciones del modelo

            foreach ($this->relationships->values() as $relationName => $model) {
                $relationProperty = $this->getReflectiveClass()->getProperty($this, $relationName, $reflection);

                if (!is_null($relationProperty)) {
                    $relationship = new Relationship($relationProperty, $model);

                    if ($relationProperty instanceof HasOneOrMany) {
                        $relationships->attachChildren($relationName, $relationship);
                    }
                    
                    else if ($relationProperty instanceof BelongsTo) {
                        $relationships->attachParent($relationName, $relationship);
                    }
                }
            }

            return $relationships; // Retornando agregaciones definidas del modelo
        }
        
        return null; // Modelo del proceso no tiene relaciones establecidas para mapeo
    }
    
    public function getReferences(): array {
        return $this->references;
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
     * @return ReflectiveClass
     */
    protected function getReflectiveClass(): ReflectiveClass {
        if (is_null(self::$reflective)) {
            self::$reflective = new ReflectiveClass(); // Instanciando
        }
        
        return self::$reflective; // Retornando instancia única
    }
    
    /**
     * 
     * @return array
     */
    protected function getConversionsDefault(): array {
        return [];
    }

    /**
     * 
     * @param array $references
     * @return array
     */
    private function getReferencesEloquent(?array $references): array {
        return $this->getMapper()->getReferencesFormat(is_null($references) ? $this->getReferences() : $references);
    }
}