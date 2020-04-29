<?php

namespace Xofttion\ORM\Contracts;

use Illuminate\Database\Eloquent\Collection;

use Xofttion\ORM\Contracts\IModel;

interface IQuery {
    
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
     * @return IModel|null
     */
    public function getModel(): ?IModel;

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
     * @return Collection
     */
    public function rows(array $columns = ["*"]): Collection;

    /**
     * 
     * @param array $aggregations
     * @return Collection
     */
    public function catalog(array $aggregations = []): Collection;
    
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
     * @param array $aggregations
     * @return IModel|null
     */
    public function record(?int $id = null, array $aggregations = []): ?IModel;
    
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
     * @param object $value
     * @return IQuery
     */
    public function where(string $column, string $operator, $value): IQuery;
    
    /**
     * 
     * @param string $column
     * @param string $operator
     * @param object $value
     * @return IQuery
     */
    public function orWhere(string $column, string $operator, $value): IQuery;
    
    /**
     * 
     * @param string $column
     * @param object $value
     * @return IQuery
     */
    public function whereEqual(string $column, $value): IQuery;
    
    /**
     * 
     * @param string $column
     * @param object $value
     * @return IQuery
     */
    public function orWhereEqual(string $column, $value): IQuery;
    
    /**
     * 
     * @param string $column
     * @param object $value
     * @return IQuery
     */
    public function whereGreater(string $column, $value): IQuery;
    
    /**
     * 
     * @param string $column
     * @param object $value
     * @return IQuery
     */
    public function orWhereGreater(string $column, $value): IQuery;
    
    /**
     * 
     * @param string $column
     * @param object $value
     * @return IQuery
     */
    public function whereSmaller(string $column, $value): IQuery;
    
    /**
     * 
     * @param string $column
     * @param object $value
     * @return IQuery
     */
    public function orWhereSmaller(string $column, $value): IQuery;
    
    /**
     * 
     * @param string $column
     * @param object $value
     * @return IQuery
     */
    public function whereEqualGreater(string $column, $value): IQuery;
    
    /**
     * 
     * @param string $column
     * @param object $value
     * @return IQuery
     */
    public function orWhereEqualGreater(string $column, $value): IQuery;
    
    /**
     * 
     * @param string $column
     * @param object $value
     * @return IQuery
     */
    public function whereEqualSmaller(string $column, $value): IQuery;
    
    /**
     * 
     * @param string $column
     * @param object $value
     * @return IQuery
     */
    public function orWhereEqualSmaller(string $column, $value): IQuery;
    
    /**
     * 
     * @param string $column
     * @param object $value
     * @return IQuery
     */
    public function whereDifferent(string $column, $value): IQuery;
    
    /**
     * 
     * @param string $column
     * @param object $value
     * @return IQuery
     */
    public function orWhereDifferent(string $column, $value): IQuery;

    /**
     * 
     * @param string $column
     * @param object $value
     * @param bool $not
     * @return IQuery
     */
    public function whereIn(string $column, $value, bool $not = false): IQuery;

    /**
     * 
     * @param string $column
     * @param object $value
     * @param bool $not
     * @return IQuery
     */
    public function orWhereIn(string $column, $value, bool $not = false): IQuery;
    
    /**
     * 
     * @param string $column
     * @param object $value
     * @param bool $not
     * @return IQuery
     */
    public function whereLike(string $column, $value, bool $not = false): IQuery;
    
    /**
     * 
     * @param string $column
     * @param object $value
     * @param bool $not
     * @return IQuery
     */
    public function orWhereLike(string $column, $value, bool $not = false): IQuery;
    
    /**
     * 
     * @param string $column
     * @param object $value
     * @param bool $not
     * @return IQuery
     */
    public function whereBetween(string $column, $value, bool $not = false): IQuery;
    
    /**
     * 
     * @param string $column
     * @param object $value
     * @param bool $not
     * @return IQuery
     */
    public function orWhereBetween(string $column, $value, bool $not = false): IQuery;
    
    /**
     * 
     * @param string $column
     * @param bool $not
     * @return IQuery
     */
    public function whereIsNull(string $column, bool $not = false): IQuery;
    
    /**
     * 
     * @param string $column
     * @param bool $not
     * @return IQuery
     */
    public function orWhereIsNull(string $column, bool $not = false): IQuery;
    
    /**
     * 
     * @param IWhere $where
     * @return IQuery
     */
    public function whereAttach(IWhere $where): IQuery;
    
    /**
     * 
     * @param array $columns
     * @return IQuery
     */
    public function groupBy(...$columns): IQuery;

    /**
     * 
     * @param string $column
     * @param bool $asc
     * @return IQuery
     */
    public function orderBy(string $column, bool $asc = true): IQuery;
    
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