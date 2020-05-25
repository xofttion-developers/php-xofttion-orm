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
        
        $model->setData(
            $this->getDataFormat($data, $model->getConversions())
        );
        
        return $model; // Retornando modelo con sus atributos cargados
    }
    
    public function getDataFormat(array $source, array $conversions): array {
        $formatArray = []; // Datos de array formateado resultante
        
        foreach ($source as $key => $value) {
            $formatArray[$key] = $this->getValueKey($key, $value, $conversions);
        } // Recorriendo origen de datos para formatear
        
        return $formatArray; // Retornando array formateado resultante
    }
    
    public function getReferencesFormat(array $references): array {
        $referencesFormat = []; // Agregaciones en formato de Eloquent
        
        foreach ($references as $key => $value) {
            if (is_int($key)) {
                array_push($referencesFormat, $value); // Nombre de la relación
            } else {
                $referencesFormat[$key] = $this->getQueryReference($value);
            }
        }
        
        return $referencesFormat; // Retornando relaciones del modelo
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
    private function getQueryReference($value): Closure {
        return function ($query) use ($value) {
            return $query->with($this->getReferencesFormat($value));
        };
    }
}