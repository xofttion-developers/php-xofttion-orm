<?php

namespace Xofttion\ORM\Sql;

use Closure;

use Xofttion\ORM\Contracts\IWhere;

class Where implements IWhere {
    
    // Atributos de la clase Where
    
    /**
     *
     * @var array 
     */
    protected $predicates = [];

    // Métodos sobrescritos de la interfaz IWhere

    public function getPredicates(): array {
        return $this->predicates;
    }

    public function condition(string $column, string $operator, $value, bool $or = false): IWhere {
        array_push($this->predicates, new Condition($column, $operator, $value, $or)); return $this; 
    }

    public function equal(string $column, $value, bool $or = false): IWhere {
        return $this->condition($column, "=", $value, $or);
    }

    public function greater(string $column, $value, bool $or = false): IWhere {
        return $this->condition($column, ">", $value, $or);
    }

    public function smaller(string $column, $value, bool $or = false): IWhere {
        return $this->condition($column, "<", $value, $or);
    }

    public function equalGreater(string $column, $value, bool $or = false): IWhere {
        return $this->condition($column, ">=", $value, $or);
    }

    public function equalSmaller(string $column, $value, bool $or = false): IWhere {
        return $this->condition($column, "<=", $value, $or);
    }

    public function different(string $column, $value, bool $or = false): IWhere {
        return $this->condition($column, "<>", $value, $or);
    }

    public function in(string $column, $value, bool $or = false, bool $not = false): IWhere {
        array_push($this->predicates, new In($column, $value, $or, $not)); return $this; 
    }

    public function between(string $column, $value, bool $or = false, bool $not = false): IWhere {
        array_push($this->predicates, new Between($column, $value, $or, $not)); return $this;
    }

    public function like(string $column, $value, bool $or = false, bool $not = false): IWhere {
        array_push($this->predicates, new Like($column, $value, $or, $not)); return $this; 
    }

    public function isNull(string $column, bool $or = false, bool $not = false): IWhere {
        array_push($this->predicates, new IsNull($column, $or, $not)); return $this; 
    }
    
    public function raw(string $sentence): IWhere {
        array_push($this->predicates, new Raw($sentence)); return $this; 
    }
    
    public function nested(Closure $closureWhere): IWhere {
        $where = new static(); // Instanciando nuevo where
        
        $closureWhere($where); $this->attach($where); 
        
        return $this; // Retornando como interfaz fluida
    }
    
    public function attach(IWhere $where): IWhere {
        array_push($this->predicates, $where); return $this;
    }
}