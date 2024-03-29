<?php

namespace App\Traits;

use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Model;

trait UpdateFromDTO
{
    /**
     * @param Model $entity
     * @param mixed $dto
     * 
     * @return bool
     */
    public function updateFromDto(Model $entity, $dto): bool
    {
        $dtoProperties = get_object_vars($dto);
        foreach ($dtoProperties as $property => $value) {
            if ($value !== null) {
                $property = StringHelper::camelToSnake($property);
                $entity->$property = $value;
            }
        }

        return $entity->save();
    }
}
