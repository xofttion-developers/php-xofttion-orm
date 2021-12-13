<?php

namespace Xofttion\ORM\Contracts;

use Closure;
use Illuminate\Database\Eloquent\Collection;
use Xofttion\ORM\Contracts\IModel;

interface IQuery
{

    // Métodos de la interfaz IQuery

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
     * @return IModel
     */
    public function getModel(): IModel;
    
    /**
     * 
     * @param string $table
     * @return void
     */
    public function setTable(string $table): void;
    
    /**
     * 
     * @return void
     */
    public function activeSqlLog(): void;

    /**
     * 
     * @param array $data
     * @param array|null $hidrations
     * @return IModel
     */
    public function insert(array $data, ?array $hidrations = null): IModel;

    /**
     * 
     * @param array $columns
     * @param array|null $references
     * @return Collection
     */
    public function rows(array $columns = ["*"], ?array $references = null): Collection;

    /**
     * 
     * @param array|null $references
     * @return Collection
     */
    public function catalog(?array $references = null): Collection;

    /**
     * 
     * @param int|null $id
     * @param array $columns
     * @return IModel|null
     */
    public function find(?int $id = null, array $columns = ["*"]): ?IModel;

    /**
     * 
     * @param int|null $id
     * @param array|null $references
     * @return IModel|null
     */
    public function record(?int $id = null, ?array $references = null): ?IModel;

    /**
     * 
     * @param int $id
     * @param array $data
     * @return IModel|null
     */
    public function update(int $id, array $data): ?IModel;

    /**
     * 
     * @param int $id
     * @param array $data
     * @param array|null $hidrations
     * @return IModel|null
     */
    public function safeguard(int $id, array $data, ?array $hidrations = null): ?IModel;

    /**
     * 
     * @param int|null $id
     * @return bool
     */
    public function delete(?int $id = null): bool;

    /**
     * 
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @return IQuery
     */
    public function where(string $column, string $operator, $value): IQuery;

    /**
     * 
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @return IQuery
     */
    public function orWhere(string $column, string $operator, $value): IQuery;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @return IQuery
     */
    public function equal(string $column, $value): IQuery;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @return IQuery
     */
    public function orEqual(string $column, $value): IQuery;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @return IQuery
     */
    public function greater(string $column, $value): IQuery;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @return IQuery
     */
    public function orGreater(string $column, $value): IQuery;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @return IQuery
     */
    public function smaller(string $column, $value): IQuery;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @return IQuery
     */
    public function orSmaller(string $column, $value): IQuery;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @return IQuery
     */
    public function equalGreater(string $column, $value): IQuery;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @return IQuery
     */
    public function orEqualGreater(string $column, $value): IQuery;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @return IQuery
     */
    public function equalSmaller(string $column, $value): IQuery;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @return IQuery
     */
    public function orEqualSmaller(string $column, $value): IQuery;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @return IQuery
     */
    public function different(string $column, $value): IQuery;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @return IQuery
     */
    public function orDifferent(string $column, $value): IQuery;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @param bool $not
     * @return IQuery
     */
    public function in(string $column, $value, bool $not = false): IQuery;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @param bool $not
     * @return IQuery
     */
    public function orIn(string $column, $value, bool $not = false): IQuery;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @param bool $not
     * @return IQuery
     */
    public function like(string $column, $value, bool $not = false): IQuery;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @param bool $not
     * @return IQuery
     */
    public function orLike(string $column, $value, bool $not = false): IQuery;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @param bool $not
     * @return IQuery
     */
    public function between(string $column, $value, bool $not = false): IQuery;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @param bool $not
     * @return IQuery
     */
    public function orBetween(string $column, $value, bool $not = false): IQuery;

    /**
     * 
     * @param string $column
     * @param bool $not
     * @return IQuery
     */
    public function isNull(string $column, bool $not = false): IQuery;

    /**
     * 
     * @param string $column
     * @param bool $not
     * @return IQuery
     */
    public function orIsNull(string $column, bool $not = false): IQuery;

    /**
     * 
     * @param string $sentence
     * @return IQuery
     */
    public function whereRaw(string $sentence): IQuery;

    /**
     * 
     * @param Closure $closureWhere
     * @return IQuery
     */
    public function nested(Closure $closureWhere): IQuery;

    /**
     * 
     * @param array $columns
     * @return IQuery
     */
    public function groupBy(array $columns): IQuery;

    /**
     * 
     * @param string $column
     * @param bool $asc
     * @return IQuery
     */
    public function orderBy(string $column, bool $asc = true): IQuery;

    /**
     * 
     * @param string $relation
     * @param string $relationColumn
     * @param string $parentColumn
     * @param string $operator
     * @return IQuery
     */
    public function innerJoin(string $relation, string $relationColumn, string $parentColumn, string $operator = "="): IQuery;

    /**
     * 
     * @param string $relation
     * @param string $relationColumn
     * @param string $parentColumn
     * @param string $operator
     * @return IQuery
     */
    public function leftJoin(string $relation, string $relationColumn, string $parentColumn, string $operator = "="): IQuery;

    /**
     * 
     * @param int $count
     * @return IQuery
     */
    public function limit(int $count): IQuery;

    /**
     * 
     * @param int $value
     * @return IQuery
     */
    public function offset(int $value): IQuery;
}
