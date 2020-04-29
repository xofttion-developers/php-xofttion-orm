<?php

namespace Xofttion\ORM\Utils;

use Closure;

use Xofttion\ORM\Contracts\IModelMapper;
use Xofttion\ORM\Contracts\IModel;

class ModelMapper implements IModelMapper {
    
    // Atributos de la clase ModelMapper
    
    /**
     *
     * @var ModelMapper 
     */
    private static $instance = null;
    
    // Constructor de la clase ModelMapper
    
    private function __construct() {
        
    }
    
    // Métodos estáticos de la clase ModelMapper

    /**
     * 
     * @return ModelMapper
     */
    public static function getInstance(): ModelMapper {
        if (is_null(self::$instance)) {
            self::$instance = new static(); // Instanciando ModelMapper
        } 
        
        return self::$instance; // Retornando ModelMapper
    }
    
    // Métodos sobrescritos de la interfaz IMapper
    
    public function ofArray(IModel $model, ?array $data): ?IModel {
        if (is_null($data)) { 
            return null; // No se puede realizar mapeo del modelo
        } 
        
        $result = $this->getDataFormat($data, $model->getConversions());
        
        foreach ($result as $key => $value) {
            $model[$key] = $value;
        } // Cargando los datos del objeto en el modelo
        
        return $model; // Retornando modelo con sus atributos cargados
    }
    
    public function getDataFormat(array $source, array $conversions): array {
        $formatArray = []; // Datos de array formateado resultante
        
        foreach ($source as $key => $value) {
            $formatArray[$key] = $this->getValueKey($key, $value, $conversions);
        } // Recorriendo origen de datos para formatear
        
        return $formatArray; // Retornando array formateado resultante
    }
    
    public function getAggregationsFormat(array $aggregations): array {
        $eloquentAggregations = []; // Agregaciones en formato de Eloquent
        
        foreach ($aggregations as $key => $value) {
            if (is_int($key)) {
                array_push($eloquentAggregations, $value); // Nombre de la relación
            } else {
                $eloquentAggregations[$key] = $this->getEloquentQuery($value);
            }
        }
        
        return $eloquentAggregations; // Retornando agregaciones del model
    }
    
    // Métodos de la clase ModelMapper

    /**
     * 
     * @param string $key
     * @param object $value
     * @param array $conversions
     * @return object
     */
    private function getValueKey(string $key, $value, array $conversions) {
        if (isset($conversions[$key])) {
            return $this->getValueConvert($conversions[$key], $value);
        } else {
            return $value; // Retornando valor predeterminado
        }
    }
    
    /**
     * 
     * @param string $type
     * @param object $value
     * @return object
     */
    protected function getValueConvert(string $type, $value) {
        switch ($type) {
            default : return $value; // Valor predeterminado establecido en array
        }
    }
    
    /**
     * 
     * @param object $value
     * @return Closure
     */
    private function getEloquentQuery($value): Closure {
        return function ($query) use ($value) {
            return $query->with($this->getAggregationsFormat($value));
        };
    }
}