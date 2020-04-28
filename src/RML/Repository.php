<?php

namespace Xofttion\ORM\RML;

use Illuminate\Database\Eloquent\Collection;

use Xofttion\ORM\DAL\Query;
use Xofttion\ORM\DAL\Contracts\IModel;

use Xofttion\ORM\RML\Contracts\IRepository;
use Xofttion\ORM\RML\Contracts\IEntity;
use Xofttion\ORM\RML\Contracts\IEntityCollection;
use Xofttion\ORM\RML\Contracts\IEntityMapper;
use Xofttion\ORM\RML\Utils\EntityMapper;

class Repository implements IRepository {
    
    // Atributos de la clase Repository

    /**
     *
     * @var string 
     */
    protected $entity;
    
    /**
     *
     * @var string 
     */
    private $context;
    
    /**
     *
     * @var EntityMapper 
     */
    private $entityMapper;
    
    // Constructor de la clase Repository
    
    public function __construct(string $classEntity) {
        $this->entity = $classEntity;
    }

    // Métodos sobrescritos de la interfaz IRepository

    public function setContext(?string $context): void {
        $this->context = $context;
    }

    public function getContext(): ?string {
        return $this->context;
    }

    public function getEntity(): ?IEntity {
        return new $this->entity();
    }

    public function insert(IEntity $entity): void {
        $hidrations = $entity->getAggregations()->keys()->hidration();
        $model      = $this->getQuery()->insert($entity->toArray(), $hidrations);
        
        $this->mapper($entity, $model); // Actualizando entity generada
    }

    public function find(int $id): ?IEntity {
        return $this->createEntity($this->getQuery()->find($id));
    }

    public function findAll(): IEntityCollection {
        return $this->createCollection($this->getQuery()->rows());
    }

    public function fetch(int $id, array $aggregations = null): ?IEntity {
        if (is_null($aggregations)) {
            $aggregations = $this->getEntity()->getAggregations()->keys()->mappable();
        } // Estableciendo relaciones predeterminadas
        
        return $this->createEntity($this->getQuery()->record($id, $aggregations));
    }

    public function fetchAll(array $aggregations = null): IEntityCollection {
        if (is_null($aggregations)) {
            $aggregations = $this->getEntity()->getAggregations()->keys()->mappable();
        } // Estableciendo relaciones predeterminadas
        
        return $this->createCollection($this->getQuery()->catalog($aggregations));
    }
    
    public function resources(): IEntityCollection {
        $entity       = $this->getEntity(); // Entidad que gestiona recurso
        $aggregations = $entity->getAggregations()->keys()->mappable();
        
        return $this->createCollection($this->getQuery($entity)->catalog($aggregations));
    }

    public function update(int $id, array $data): void {
        $this->getQuery()->update($id, $data);
    }

    public function save(IEntity $entity): void {
        $hidrations = $entity->getAggregations()->keys()->hidration();
        $model      = $this->getQuery()->update($entity->getPrimaryKey(), $entity->toArray(), $hidrations);
        
        $this->mapper($entity, $model); // Actualizando entity generada
    }
    
    public function delete(IEntity $entity): void {
        $this->getQuery()->delete($entity->getPrimaryKey());
    }
    
    // Métodos de la clase Repository
    
    /**
     * 
     * @param IEntityMapper $entityMapper
     * @return void
     */
    public function setMapper(IEntityMapper $entityMapper): void {
        $this->entityMapper = $entityMapper;
    }

    /**
     * 
     * @return EntityMapper
     */
    public function getMapper(): IEntityMapper {
        if (is_null($this->entityMapper)) {
            $this->entityMapper = $this->getInstanceMapper();
        } // Instanciando mapeador del repositorio
        
        return $this->entityMapper; // Retornando mapeador
    }
    
    /**
     * 
     * @return IEntityMapper
     */
    protected function getInstanceMapper(): IEntityMapper {
        return EntityMapper::getInstance();
    }

    /**
     * 
     * @param IEntity|null $entity
     * @return Query
     */
    protected function getQuery(?IEntity $entity = null): Query {
        if (is_null($entity)) {
            $entity = $this->getEntity();
        } // Definiendo entity
        
        $query = new Query($entity->getTable());
        $query->setContext($this->getContext());
        
        return $query; // Retornando Query para Entity
    }
    
    /**
     * 
     * @param IEntity $entity
     * @param IModel $model
     * @return void
     */
    protected function mapper(IEntity $entity, IModel $model): void {
        if (!is_null($model)) {
            $this->getMapper()->clean()->ofArray($entity, $model->toArray());
        } // Mapeando modelo en entidad
    }
    
    /**
     * 
     * @param IModel|null $model
     * @param string|null $classEntity
     * @return IEntity|null
     */
    protected function createEntity(?IModel $model, ?string $classEntity = null): ?IEntity {
        if (is_null($model)) { 
            return null; // Modelo se encuentra indefinido
        } 
        
        $entity = (is_null($classEntity)) ? $this->getEntity() : new $classEntity();
        
        $this->mapper($entity, $model);
        
        return $entity; // Retornando la entidad generada
    }
    
    /**
     * 
     * @param Collection $models
     * @param string|null $classEntity
     * @return IEntityCollection
     */
    protected function createCollection(Collection $models, ?string $classEntity = null): IEntityCollection {
        $entities = $this->getCollection(); // Colección de entidades
        
        foreach ($models as $model) {
            $entities->attach($this->createEntity($model, $classEntity));
        } // Mapeando listado de modelos
        
        return $entities; // Retornado listado de entidades generado
    }
    
    /**
     * 
     * @return IEntityCollection
     */
    protected function getCollection(): IEntityCollection {
        return new EntityCollection();
    }
}