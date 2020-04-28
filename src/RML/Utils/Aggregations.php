<?php

namespace Xofttion\ORM\RML\Utils;

use Closure;

use Xofttion\Kernel\Structs\DataDictionary;

use Xofttion\ORM\RML\Contracts\IAggregationsKeys;
use Xofttion\ORM\RML\Contracts\IAggregation;
use Xofttion\ORM\RML\Contracts\IAggregations;

class Aggregations implements IAggregations {
    
    // Atributos de la clase Aggregations
    
    /**
     *
     * @var DataDictionary 
     */
    private $aggregations;
    
    // Constructor de la clase Aggregations
    
    /**
     * 
     */
    public function __construct() {
        $this->aggregations = new DataDictionary();
    }
    
    // Métodos de la clase Aggregations
    
    /**
     * 
     * @param Closure $closure
     * @return array
     */
    protected function forProcess(Closure $closure): array {
        $aggregations = []; // Contenedor de relaciones para gestion de datos
        
        foreach ($this->aggregations->values() as $key => $value) {
            if ($closure($value)) {
                $aggregations[$key] = $value;
            }
        } // Agregando relaciones para gestion de datos
        
        return $aggregations; // Retornando relaciones para gestion de datos
    }
    
    // Métodos sobrescritos de la clase IAggregations
    
    public function attach(string $key, IAggregation $aggregation): void {
        $this->aggregations->attach($key, $aggregation);
    }
    
    public function contains(string $key): bool {
        return $this->aggregations->contains($key);
    }
    
    public function getValue(string $key): ?IAggregation {
        return $this->aggregations->getValue($key);
    }
    
    public function hasOne(string $key, string $class, bool $mappable = true): IAggregations {
        $this->attach($key, new HasOne($class, $mappable)); 
        
        return $this; // Retornando instancia como interfaz fluida
    }
    
    public function hasMany(string $key, string $class, bool $mappable = true): IAggregations {
        $this->attach($key, new HasMany($class, $mappable)); 
        
        return $this; // Retornando instancia como interfaz fluida
    }
    
    public function composedBy(string $key, string $class, bool $mappable = true): IAggregations {
        $this->attach($key, new ComposedBy($class, $mappable)); 
        
        return $this; // Retornando instancia como interfaz fluida
    }
    
    public function belongTo(string $key, string $class, ?string $column = null, bool $mappable = true): IAggregations {
        if (is_null($column)) {
            $column = "{$key}_id"; // Redefiniendo valor de clave de la columna 
        }
        
        $this->attach($key, new BelongTo($class, $column, $mappable));
        
        return $this; // Retornando instancia como interfaz fluida
    }
    
    public function containTo(string $key, string $class, bool $mappable = true): IAggregations {
        $this->attach($key, new ContainTo($class, $mappable)); 
        
        return $this; // Retornando instancia como interfaz fluida
    }
    
    public function keys(): IAggregationsKeys {
        return AggregationsKeys::getInstance()->setAggregations($this->aggregations->values());
    }
    
    public function forCascade(): array {
        return $this->forProcess(function (IAggregation $aggregation) { return $aggregation->isCascade(); });
    }
    
    public function forHidration(): array {
        return $this->forProcess(function (IAggregation $aggregation) { return $aggregation->isHidration(); });
    }
    
    public function forBelong(): array {
        return $this->forProcess(function (IAggregation $aggregation) { return $aggregation->isBelong(); });
    }
    
    public function forMappable(): array {
        return $this->forProcess(function (IAggregation $aggregation) { return $aggregation->isMappable(); });
    }
}