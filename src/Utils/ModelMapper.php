<?php

namespace Xofttion\ORM\Utils;

use Closure;
use Xofttion\ORM\Contracts\IModelMapper;
use Xofttion\ORM\Contracts\IModel;

class ModelMapper implements IModelMapper
{

    // Atributos de la clase ModelMapper

    /**
     *
     * @var ModelMapper 
     */
    private static $instance = null;

    // Constructor de la clase ModelMapper

    private function __construct()
    {

    }

    // Métodos estáticos de la clase ModelMapper

    /**
     * 
     * @return ModelMapper
     */
    public static function getInstance(): ModelMapper
    {
        if (is_null(self::$instance)) {
            self::$instance = new static ();
        }

        return self::$instance;
    }

    // Métodos sobrescritos de la interfaz IMapper

    public function ofArray(IModel $model, ?array $data): ?IModel
    {
        if (is_null($data)) {
            return null;
        }

        $model->setData($this->getDataFormat($data, $model->getConversions()));

        return $model;
    }

    public function getDataFormat(array $source, array $conversions): array
    {
        $formatArray = [];

        foreach ($source as $key => $value) {
            $formatArray[$key] = $this->getValueKey($key, $value, $conversions);
        }

        return $formatArray;
    }

    public function getReferencesFormat(array $references): array
    {
        $referencesFormat = [];

        foreach ($references as $key => $value) {
            if (is_int($key)) {
                array_push($referencesFormat, $value);
            }
            else {
                $referencesFormat[$key] = $this->getQueryReference($value);
            }
        }

        return $referencesFormat;
    }

    // Métodos de la clase ModelMapper

    /**
     * 
     * @param string $key
     * @param mixed $value
     * @param array $conversions
     * @return mixed
     */
    private function getValueKey(string $key, $value, array $conversions)
    {
        if (isset($conversions[$key])) {
            return $this->getValueConvert($conversions[$key], $value);
        }
        else {
            return $value;
        }
    }

    /**
     * 
     * @param string $type
     * @param mixed $value
     * @return mixed
     */
    protected function getValueConvert(string $type, $value)
    {
        switch ($type) {
            default: {
                return $value;
            }
        }
    }

    /**
     * 
     * @param mixed $value
     * @return Closure
     */
    private function getQueryReference($value): Closure
    {
        return function ($query) use ($value) {
            return $query->with($this->getReferencesFormat($value));
        };
    }
}
