<?php

namespace App\Support\Traits;

trait CanConvertToArray
{
    /**
     * Convert the data object to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = [];

        // Get all public properties and their values
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $name = $property->getName();
            $value = $this->{$name};

            // Include the property even if it's null
            $array[$name] = $value;
        }

        return $array;
    }
}
