<?php

namespace Xofttion\ORM;

use Closure;
use Illuminate\Database\Eloquent\Collection;
use Xofttion\ORM\Contracts\IModel;
use Xofttion\ORM\Contracts\IQuery;
use Xofttion\ORM\Contracts\IWhere;
use Xofttion\ORM\Sql\Where;
use Xofttion\ORM\Sql\GroupBy;
use Xofttion\ORM\Sql\OrderBy;
use Xofttion\ORM\Sql\InnerJoin;
use Xofttion\ORM\Sql\LeftJoin;
use Xofttion\ORM\Sql\Limit;
use Xofttion\ORM\Sql\Offset;
use Xofttion\ORM\Utils\Builder;

class Query implements IQuery
{

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
     * @var string
     */
    private $table;
    
    /**
     *
     * @var bool
     */
    private $sqlLog = false;

    /**
     *
     * @var Where 
     */
    private $where;

    /**
     *
     * @var GroupBy 
     */
    private $groupBy;

    /**
     *
     * @var array 
     */
    private $ordersBy;

    /**
     *
     * @var array 
     */
    private $joins;

    /**
     *
     * @var array 
     */
    private $clauses;

    // Constructor de la clase Query

    public function __construct(string $model)
    {
        $this->model = $model;
    }

    // Métodos sobrescritos de la interfaz IQuery

    public function setContext(?string $context): void
    {
        if (is_defined($context)) {
            $this->context = $context; // Conexión
        }
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function getModel(): IModel
    {
        $model = (new $this->model());

        $model->setContext($this->getContext());

        return $model;
    }
    
    public function setTable(string $table): void {
        $this->table = $table;
    }
    
    public function activeSqlLog(): void {
        $this->sqlLog = true;
    }

    public function insert(array $data, ?array $hidrations = null): IModel
    {
        $model = $this->getModel();
        $model->register($data);
        
        if (is_null($hidrations) || empty($hidrations)) {
            return $model;
        } 
        else {
            return $model->fresh($hidrations);
        }
    }

    public function rows(array $columns = ["*"], ?array $references = null): Collection
    {
        $model = $this->getModel();
        
        $builder = $this->getBuilder($model);
        $builder->select($columns);
        
        if (is_defined($references)) {
            $relations = $this->getReferencesFormat($model, $references);
            
            $builder->with($relations);
        }
        
        $this->builderLog($builder);
        
        return $builder->get();
    }

    public function catalog(?array $references = null): Collection
    {
        $model = $this->getModel();
        
        $relations = $this->getReferencesFormat($model, $references);
        
        $builder = $this->getBuilder($model);
        $builder->with($relations);
        
        $this->builderLog($builder);

        return $builder->get();
    }

    public function find(?int $id = null, array $columns = ["*"]): ?IModel
    {
        $model = $this->getModel();

        if (!is_null($id)) {
            $this->equal($model->getPrimaryKey(), $id);
        }
        
        $builder = $this->getBuilder($model);
        $builder->select($columns);
        
        $this->builderLog($builder);

        return $builder->first();
    }

    public function record(?int $id = null, ?array $references = null): ?IModel
    {
        $model = $this->getModel();
        
        $relations = $this->getReferencesFormat($model, $references);

        if (!is_null($id)) {
            $this->equal($model->getPrimaryKey(), $id);
        }
        
        $builder = $this->getBuilder($model);
        $builder->with($relations);
        
        $this->builderLog($builder);

        return $builder->first();
    }

    public function update(int $id, array $data): ?IModel
    {
        $model = $this->find($id);

        if (!is_null($model)) {
            $dataFormat = $this->attachNulleableValues($model, $data);

            if (!empty($dataFormat)) {
                $model->setData($dataFormat);
                $model->save();
            }

            return $model;
        }
        else {
            return null;
        }
    }

    public function safeguard(int $id, array $data, ?array $hidrations = null): ?IModel
    {
        $model = $this->getModel();

        $this->equal($model->getPrimaryKey(), $id);

        $dataFormat = $this->dettachModifiableValues($model, $data);

        if (empty($dataFormat)) {
            return null;
        }

        $rows = $this->getBuilder($model, false)->update($dataFormat);

        if ($rows > 0) {
            return is_null($hidrations) || empty($hidrations) ? 
                $this->find($id) :
                $this->record($id, $hidrations);
        }
        else {
            return null;
        }
    }

    public function delete(?int $id = null): bool
    {
        $model = $this->getModel();

        if (!is_null($id)) {
            $this->equal($model->getPrimaryKey(), $id);
        }
        
        $builder = $this->getBuilder($model, false);

        return $builder->delete() > 0;
    }

    public function where(string $column, string $operator, $value): IQuery
    {
        $this->getWhereQuery()->condition($column, $operator, $value);

        return $this;
    }

    public function orWhere(string $column, string $operator, $value): IQuery
    {
        $this->getWhereQuery()->condition($column, $operator, $value, true);

        return $this;
    }

    public function equal(string $column, $value): IQuery
    {
        $this->getWhereQuery()->equal($column, $value);

        return $this;
    }

    public function orEqual(string $column, $value): IQuery
    {
        $this->getWhereQuery()->equal($column, $value, true);

        return $this;
    }

    public function greater(string $column, $value): IQuery
    {
        $this->getWhereQuery()->greater($column, $value);

        return $this;
    }

    public function orGreater(string $column, $value): IQuery
    {
        $this->getWhereQuery()->greater($column, $value, false);

        return $this;
    }

    public function smaller(string $column, $value): IQuery
    {
        $this->getWhereQuery()->smaller($column, $value);

        return $this;
    }

    public function orSmaller(string $column, $value): IQuery
    {
        $this->getWhereQuery()->smaller($column, $value, true);

        return $this;
    }

    public function equalGreater(string $column, $value): IQuery
    {
        $this->getWhereQuery()->equalGreater($column, $value);

        return $this;
    }

    public function orEqualGreater(string $column, $value): IQuery
    {
        $this->getWhereQuery()->equalGreater($column, $value, true);

        return $this;
    }

    public function equalSmaller(string $column, $value): IQuery
    {
        $this->getWhereQuery()->equalSmaller($column, $value);

        return $this;
    }

    public function orEqualSmaller(string $column, $value): IQuery
    {
        $this->getWhereQuery()->equalSmaller($column, $value, true);

        return $this;
    }

    public function different(string $column, $value): IQuery
    {
        $this->getWhereQuery()->different($column, $value);

        return $this;
    }

    public function orDifferent(string $column, $value): IQuery
    {
        $this->getWhereQuery()->different($column, $value, true);

        return $this;
    }

    public function in(string $column, $value, bool $not = false): IQuery
    {
        $this->getWhereQuery()->in($column, $value, false, $not);

        return $this;
    }

    public function orIn(string $column, $value, bool $not = false): IQuery
    {
        $this->getWhereQuery()->in($column, $value, true, $not);

        return $this;
    }

    public function between(string $column, $value, bool $not = false): IQuery
    {
        $this->getWhereQuery()->between($column, $value, false, $not);

        return $this;
    }

    public function orBetween(string $column, $value, bool $not = false): IQuery
    {
        $this->getWhereQuery()->between($column, $value, true, $not);

        return $this;
    }

    public function like(string $column, $value, bool $not = false): IQuery
    {
        $this->getWhereQuery()->like($column, $value, false, $not);

        return $this;
    }

    public function orLike(string $column, $value, bool $not = false): IQuery
    {
        $this->getWhereQuery()->like($column, $value, true, $not);

        return $this;
    }

    public function isNull(string $column, bool $not = false): IQuery
    {
        $this->getWhereQuery()->isNull($column, false, $not);

        return $this;
    }

    public function orIsNull(string $column, bool $not = false): IQuery
    {
        $this->getWhereQuery()->isNull($column, true, $not);

        return $this;
    }

    public function whereRaw(string $sentence): IQuery
    {
        $this->getWhereQuery()->raw($sentence);

        return $this;
    }

    public function nested(Closure $closureWhere): IQuery
    {
        $where = $this->getInstanceWhere();

        $closureWhere($where);
        $this->getWhereQuery()->attach($where);

        return $this;
    }

    public function groupBy(array $columns): IQuery
    {
        $this->getInstanceGroupBy($columns);

        return $this;
    }

    public function orderBy(string $column, bool $asc = true): IQuery
    {
        if (is_null($this->ordersBy)) {
            $this->ordersBy = [];
        }

        $this->ordersBy[] = new OrderBy($column, $asc);

        return $this;
    }

    public function innerJoin(string $relation, string $relationColumn, string $parentColumn, string $operator = "="): IQuery
    {
        if (is_null($this->joins)) {
            $this->joins = [];
        }

        $this->joins[] = new InnerJoin($relation, $relationColumn, $parentColumn, $operator);

        return $this;
    }

    public function leftJoin(string $relation, string $relationColumn, string $parentColumn, string $operator = "="): IQuery
    {
        if (is_null($this->joins)) {
            $this->joins = [];
        }

        $this->joins[] = new LeftJoin($relation, $relationColumn, $parentColumn, $operator);

        return $this;
    }

    public function limit(int $count): IQuery
    {
        if (is_null($this->clauses)) {
            $this->clauses = [];
        }

        $this->clauses[] = new Limit($count);

        return $this;
    }

    public function offset(int $value): IQuery
    {
        if (is_null($this->clauses)) {
            $this->clauses = [];
        }

        $this->clauses[] = new Offset($value);

        return $this;
    }

    // Métodos de la clase Query

    /**
     * 
     * @param IModel $model
     * @param array $references
     * @return array
     */
    private function getReferencesFormat(IModel $model, ?array $references): array
    {
        $mapper = $model->getMapper();
        
        if (is_null($references)) {
            return $mapper->getReferencesFormat($model->getReferences());
        }
        else {
            return $mapper->getReferencesFormat($references);
        }
    }

    /**
     * 
     * @param IModel $model
     * @param array $data
     * @return array
     */
    private function attachNulleableValues(IModel $model, array $data): array
    {
        $dataFormat = $model->getDataFormat($data);

        foreach ($model->getNulleables() as $nulleable) {
            if (!isset($dataFormat[$nulleable])) {
                $dataFormat[$nulleable] = null;
            }
        }

        return $dataFormat;
    }

    /**
     * 
     * @param IModel $model
     * @param array $data
     * @return array
     */
    private function dettachModifiableValues(IModel $model, array $data): array
    {
        $dataFormat = $this->attachNulleableValues($model, $data);

        foreach ($model->getUnchangeables() as $unchangeable) {
            if (isset($dataFormat[$unchangeable])) {
                unset($dataFormat[$unchangeable]);
            }
        }

        return $dataFormat;
    }

    /**
     * 
     * @return IWhere|null
     */
    protected function getWhere(): ?IWhere
    {
        return $this->where;
    }

    /**
     * 
     * @return IWhere
     */
    protected function getWhereQuery(): IWhere
    {
        if (is_null($this->where)) {
            $this->where = $this->getInstanceWhere();
        }

        return $this->where;
    }

    /**
     * 
     * @return IWhere
     */
    protected function getInstanceWhere(): IWhere
    {
        return new Where();
    }

    /**
     * 
     * @return GroupBy|null
     */
    protected function getGroupBy(): ?GroupBy
    {
        return $this->groupBy;
    }

    /**
     * 
     * @return GroupBy
     */
    protected function getInstanceGroupBy(array $columns): GroupBy
    {
        if (is_null($this->groupBy)) {
            $this->groupBy = new GroupBy($columns);
        }

        return $this->groupBy;
    }

    /**
     * 
     * @return array|null
     */
    protected function getOrders(): ?array
    {
        return $this->ordersBy;
    }

    /**
     * 
     * @return array|null
     */
    protected function getJoins(): ?array
    {
        return $this->joins;
    }

    /**
     * 
     * @return array|null
     */
    protected function getClauses(): ?array
    {
        return $this->clauses;
    }

    /**
     * 
     * @param Model $model
     * @param bool $isSelect
     * @return Builder
     */
    protected function getBuilder(Model $model, bool $isSelect = true): Builder
    {
        $builder = $model->newBuilder();
        
        if (is_defined($this->table)) {
            $builder->setTable($this->table);
        }

        $this->flushQueryWhere($builder, $this->getWhere());

        if ($isSelect) {
            $this->flushQueryGroups($builder);
            $this->flushQueryOrders($builder);
            $this->flushQueryJoins($builder);
            $this->flushQueryClauses($builder);
        }

        return $builder;
    }

    /**
     * 
     * @param Builder $builder
     * @param IWhere|null $where
     * @return void
     */
    private function flushQueryWhere(Builder $builder, ?IWhere $where): void
    {
        if (!is_null($where)) {
            foreach ($where->getPredicates() as $predicate) {
                if ($predicate instanceof IWhere) {
                    $builder->where(function ($query) use ($predicate) {
                        $this->flushQueryWhere($query, $predicate);
                    });
                }
                else {
                    $predicate->flush($builder);
                }
            }
        }
    }

    /**
     * 
     * @param Builder $builder
     * @return void
     */
    private function flushQueryGroups(Builder $builder): void
    {
        if (is_defined($this->getGroupBy())) {
            $this->getGroupBy()->flush($builder);
        }
    }

    /**
     * 
     * @param Builder $builder
     * @return void
     */
    private function flushQueryOrders(Builder $builder): void
    {
        if (is_array($this->getOrders())) {
            foreach ($this->getOrders() as $orderBy) {
                $orderBy->flush($builder);
            }
        }
    }

    /**
     * 
     * @param Builder $builder
     * @return void
     */
    private function flushQueryJoins(Builder $builder): void
    {
        if (is_array($this->getJoins())) {
            foreach ($this->getJoins() as $join) {
                $join->flush($builder);
            }
        }
    }

    /**
     * 
     * @param Builder $builder
     * @return void
     */
    private function flushQueryClauses(Builder $builder): void
    {
        if (is_array($this->getClauses())) {
            foreach ($this->getClauses() as $clause) {
                $clause->flush($builder);
            }
        }
    }
    
    /**
     * 
     * @param Builder $builder
     * @return void
     */
    private function builderLog(Builder $builder): void {
        $sqlLog = env("DB_SQL_LOG", false);
        
        if ($sqlLog || $this->sqlLog) {
            console_log($builder->toSql());
        }
    }
}
