<?php

namespace Xofttion\ORM\Contracts;

use Closure;

interface IWhere
{

    // Métodos de la interfaz IWhere

    /**
     * 
     * @return array
     */
    public function getPredicates(): array;

    /**
     * 
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @param bool $or
     * @return IWhere
     */
    public function condition(string $column, string $operator, $value, bool $or = false): IWhere;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @param bool $or
     * @param bool $not
     * @return IWhere
     */
    public function equal(string $column, $value, bool $or = false): IWhere;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @return IWhere
     */
    public function greater(string $column, $value, bool $or = false): IWhere;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @param bool $or
     * @return IWhere
     */
    public function smaller(string $column, $value, bool $or = false): IWhere;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @param bool $or
     * @return IWhere
     */
    public function equalGreater(string $column, $value, bool $or = false): IWhere;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @param bool $or
     * @return IWhere
     */
    public function equalSmaller(string $column, $value, bool $or = false): IWhere;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @param bool $or
     * @return IWhere
     */
    public function different(string $column, $value, bool $or = false): IWhere;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @param bool $or
     * @param bool $not
     * @return IWhere
     */
    public function in(string $column, $value, bool $or = false, bool $not = false): IWhere;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @param bool $or
     * @param bool $not
     * @return IWhere
     */
    public function like(string $column, $value, bool $or = false, bool $not = false): IWhere;

    /**
     * 
     * @param string $column
     * @param mixed $value
     * @param bool $or
     * @param bool $not
     * @return IWhere
     */
    public function between(string $column, $value, bool $or = false, bool $not = false): IWhere;

    /**
     * 
     * @param string $column
     * @param bool $or
     * @param bool $not
     * @return IWhere
     */
    public function isNull(string $column, bool $or = false, bool $not = false): IWhere;

    /**
     * 
     * @param string $sentence
     * @return IWhere
     */
    public function raw(string $sentence): IWhere;

    /**
     * 
     * @param IWhere $closureWhere
     * @return IWhere
     */
    public function nested(Closure $closureWhere): IWhere;

    /**
     * 
     * @param IWhere $where
     * @return IWhere
     */
    public function attach(IWhere $where): IWhere;
}
