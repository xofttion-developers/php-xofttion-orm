<?php

namespace Xofttion\ORM\DAL;

use Illuminate\Database\Eloquent\Collection;

use Xofttion\ORM\DAL\Contracts\IModel;
use Xofttion\ORM\DAL\Contracts\IQuery;
use Xofttion\ORM\DAL\Contracts\IWhere;
use Xofttion\ORM\DAL\Sql\Where;
use Xofttion\ORM\DAL\Sql\GroupBy;
use Xofttion\ORM\DAL\Sql\OrderBy;
use Xofttion\ORM\DAL\Sql\Limit;
use Xofttion\ORM\DAL\Sql\Offset;
use Xofttion\ORM\DAL\Utils\Builder;

class Query implements IQuery {
    
    // Atributos de la clase Query
    
    /**
     *
     * @var string 
     */
    private $context;

    /**
     *
     * @var string 
     */
    protected $model;
    
    /**
     *
     * @var Where 
     */
    private $where;
    
    /**
     *
     * @var GroupBy 
     */
    private $group;
    
    /**
     *
     * @var array 
     */
    private $orders;
    
    /**
     *
     * @var array 
     */
    private $clauses;

    // Constructor de la clase Query
    
    public function __construct(string $model) {
        $this->model = $model; 
    }

    // Métodos sobrescritos de la interfaz IQuery

    public function setContext(?string $context): void {
        if (!is_null($context)) {
            $this->context = $context; // Conexión
        }
    }

    public function getContext(): ?string {
        return $this->context;
    }

    public function getModel(): ?IModel {
        $model = (new $this->model()); // Instanciando
        
        if ($model instanceof IModel) {
            $model->setContext($this->getContext()); 
        } // Definiendo conexión del contexto
        
        return $model; // Retornando modelo generado
    }
    
    public function insert(array $data, ?array $hidrations = null): IModel {
        $model = $this->getModel(); $model->register($data); 
        
        return is_null($hidrations) ? $model : empty($hidrations) ? 
            $model : $model->fresh($hidrations);
    }

    public function rows(array $columns = ["*"]): Collection {
        return $this->getBuilder($this->getModel())->select($columns)->get();
    }

    public function catalog(array $aggregations = array()): Collection {
        $model = $this->getModel(); // Modelo para gestionar consulta
        
        if ($model instanceof IModel) {
            $aggregations = $model->getMapper()->getAggregationsFormat($aggregations);
        }
        
        return $this->getBuilder($model)->with($aggregations)->get();
    }

    public function find(?int $id = null, array $columns = ["*"]): ?IModel {
        $model = $this->getModel(); // Modelo para gestionar consulta
        
        if (!is_null($id)) {
            $this->whereEqual($model->getPrimaryKey(), $id);
        } // Se debe agregar filtro de PrimaryKey
        
        return $this->getBuilder($model)->select($columns)->first();
    }

    public function record(?int $id = null, array $aggregations = array()): ?IModel {
        $model = $this->getModel(); // Modelo para gestionar consulta
        
        if (!is_null($id)) {
            $this->whereEqual($model->getPrimaryKey(), $id);
        } // Se debe agregar filtro de PrimaryKey
        
        if ($model instanceof IModel) {
            $aggregations = $model->getMapper()->getAggregationsFormat($aggregations);
        }
        
        return $this->getBuilder($model)->with($aggregations)->first();
    }
    
    public function update(int $id, array $data, ?array $hidrations = null): ?IModel {
        $model = $this->getModel(); // Modelo para gestionar consulta
        
        if (!is_null($id)) {
            $this->whereEqual($model->getPrimaryKey(), $id);
        } // Se debe agregar filtro de PrimaryKey
        
        $rows = $this->getBuilder($model, false)->update($model->getDataFormat($data));
        
        if ($rows > 0) {
            return is_null($hidrations) || empty($hidrations) ?  
                $this->find() : // Sin hidrataciones
                $this->record(null, $hidrations);
        } else {
            return null; // No actualizó ningún registro en persistencia
        }
    }
    
    public function delete(?int $id = null): bool {
        $model = $this->getModel(); // Modelo para gestionar consulta
        
        if (!is_null($id)) {
            $this->equal($model->getPrimaryKey(), $id);
        } // Se debe agregar filtro de PrimaryKey
        
        return ($this->getBuilder($model, false)->delete() > 0);
    }

    public function where(string $column, string $operator, $value): IQuery {
        $this->getInstanceWhere()->condition($column, $operator, $value); 
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function orWhere(string $column, string $operator, $value): IQuery {
        $this->getInstanceWhere()->condition($column, $operator, $value, true);
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function whereEqual(string $column, $value): IQuery {
        $this->getInstanceWhere()->equal($column, $value); 
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function orWhereEqual(string $column, $value): IQuery {
        $this->getInstanceWhere()->equal($column, $value, true); 
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function whereGreater(string $column, $value): IQuery {
        $this->getInstanceWhere()->greater($column, $value); 
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function orWhereGreater(string $column, $value): IQuery {
        $this->getInstanceWhere()->greater($column, $value, false); 
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function whereSmaller(string $column, $value): IQuery {
        $this->getInstanceWhere()->smaller($column, $value); 
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function orWhereSmaller(string $column, $value): IQuery {
        $this->getInstanceWhere()->smaller($column, $value, true);
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function whereEqualGreater(string $column, $value): IQuery {
        $this->getInstanceWhere()->equalGreater($column, $value); 
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function orWhereEqualGreater(string $column, $value): IQuery {
        $this->getInstanceWhere()->equalGreater($column, $value, true); 
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function whereEqualSmaller(string $column, $value): IQuery {
        $this->getInstanceWhere()->equalSmaller($column, $value); 
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function orWhereEqualSmaller(string $column, $value): IQuery {
        $this->getInstanceWhere()->equalSmaller($column, $value, true); 
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function whereDifferent(string $column, $value): IQuery {
        $this->getInstanceWhere()->different($column, $value); 
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function orWhereDifferent(string $column, $value): IQuery {
        $this->getInstanceWhere()->different($column, $value, true); return $this;
    }

    public function whereIn(string $column, $value, bool $not = false): IQuery {
        $this->getInstanceWhere()->in($column, $value, false, $not); 
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function orWhereIn(string $column, $value, bool $not = false): IQuery {
        $this->getInstanceWhere()->in($column, $value, true, $not); 
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function whereBetween(string $column, $value, bool $not = false): IQuery {
        $this->getInstanceWhere()->between($column, $value, false, $not); 
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function orWhereBetween(string $column, $value, bool $not = false): IQuery {
        $this->getInstanceWhere()->between($column, $value, true, $not);
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function whereLike(string $column, $value, bool $not = false): IQuery {
        $this->getInstanceWhere()->like($column, $value, false, $not); 
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function orWhereLike(string $column, $value, bool $not = false): IQuery {
        $this->getInstanceWhere()->like($column, $value, true, $not); 
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function whereIsNull(string $column, bool $not = false): IQuery {
        $this->getInstanceWhere()->isNull($column, false, $not);
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function orWhereIsNull(string $column, bool $not = false): IQuery {
        $this->getInstanceWhere()->isNull($column, true, $not); 
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function whereAttach(IWhere $where): IQuery {
        $this->getInstanceWhere()->attach($where); 
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function groupBy(...$columns): IQuery {
        $this->getInstanceGroupBy()->setColumns($columns);
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function orderBy(string $column, bool $asc = true): IQuery {
        if (is_null($this->orders)) {
            $this->orders = []; // Inicializando listado
        }
        
        array_push($this->orders, new OrderBy($column, $asc)); 
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function limit(int $count): IQuery {
        if (is_null($this->clauses)) {
            $this->clauses = []; // Inicializando claúsulas
        }
        
        array_push($this->clauses, new Limit($count));
        
        return $this; // Retornando instancia como interfaz fluida
    }

    public function offset(int $value): IQuery {
        if (is_null($this->clauses)) {
            $this->clauses = []; // Inicializando claúsulas
        }
        
        array_push($this->clauses, new Offset($value));
        
        return $this; // Retornando instancia como interfaz fluida
    }
    
    // Métodos de la clase Query
    
    /**
     * 
     * @return IWhere|null
     */
    protected function getWhere(): ?IWhere {
        return $this->where;
    }
    
    /**
     * 
     * @return IWhere
     */
    protected function getInstanceWhere() : IWhere {
        if (is_null($this->where)) {
            $this->where = new Where(); // Inicializando Where
        }
        
        return $this->where; // Retornando Where
    }
    
    /**
     * 
     * @return GroupBy|null
     */
    protected function getGroupBy(): ?GroupBy {
        return $this->group;
    }
    
    /**
     * 
     * @return GroupBy
     */
    protected function getInstanceGroupBy() : GroupBy {
        if (is_null($this->group)) {
            $this->group = new GroupBy(); // Inicializando GroupBy
        }
        
        return $this->group; // Retornando GroupBy
    }
    
    /**
     * 
     * @return array|null
     */
    protected function getOrders(): ?array {
        return $this->orders;
    }
    
    /**
     * 
     * @return array|null
     */
    protected function getClauses(): ?array {
        return $this->clauses;
    }
    
    /**
     * 
     * @param Model $model
     * @param bool $isSelect
     * @return Builder
     */
    protected function getBuilder(Model $model, bool $isSelect = true): Builder {
        $builder = $model->newBuilder(); // Builder del modelo
        
        $this->flushQueryWhere($builder, $this->getWhere());
        
        if ($isSelect) {
            $this->flushQueryGroups($builder);  // Agrupadores
            $this->flushQueryOrders($builder);  // Ordenadores
            $this->flushQueryExtras($builder);  // Claúsulas adicionales
        }
        
        return $builder; // Retornando builder generado para proceso
    }
    
    /**
     * 
     * @param Builder $builder
     * @param IWhere|null $where
     * @return void
     */
    private function flushQueryWhere(Builder $builder, ?IWhere $where): void {
        if (!is_null($where)) {
            foreach ($where->getPredicates() as $predicate) {
                if ($predicate instanceof IWhere) {
                    $builder->where(function ($query) use ($predicate) {
                        $this->flushQueryWhere($query, $predicate);
                    });
                } else {
                    $predicate->flush($builder); // Cargando condición Where
                }
            }
        }
    }
    
    /**
     * 
     * @param Builder $builder
     * @return void
     */
    private function flushQueryGroups(Builder $builder): void {
        if (!is_null($this->getGroupBy())) {
            $this->getGroupBy()->flush($builder); // Cargando clausula 'GROUP BY'
        }
    }

    /**
     * 
     * @param Builder $builder
     * @return void
     */
    private function flushQueryOrders(Builder $builder): void {
        if (is_array($this->getOrders())) {
            foreach ($this->getOrders() as $orderBy) {
                $orderBy->flush($builder); // Cargando clausula 'ORDER BY'
            }
        }
    }

    /**
     * 
     * @param Builder $builder
     * @return void
     */
    private function flushQueryExtras(Builder $builder): void {
        if (is_array($this->getClauses())) {
            foreach ($this->getClauses() as $clause) {
                $clause->flush($builder); // Cargando claúsula
            }
        }
    }
}