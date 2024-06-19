<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

trait AttachEntityData
{
    /**
     * @param Collection $collection
     * @param string $entityFieldKey
     * @param string $entityDataKey
     * @param mixed $entityRepository
     * 
     * @return void
     */
    protected function setCollectionEntityData(
        Collection &$collection,
        string $entityFieldKey,
        string $entityDataKey,
        $entityRepository
    ): void {
        Log::debug("Setting collection entity data", [
            'collection' => $collection->toArray(),
            'entityFieldKey' => $entityFieldKey,
            'entityDataKey' => $entityDataKey
        ]);

        $entitiesIds = $collection->pluck($entityFieldKey)->unique()->all();
        $entitysData = $entityRepository->getByIds($entitiesIds);

        foreach ($collection as $item) {
            $item->{$entityDataKey} = $entitysData->where('id', $item->{$entityFieldKey})->first();
        }

        Log::debug("Succesfully setted collection entity data", [
            'collection' => $collection->toArray(),
            'entityFieldKey' => $entityFieldKey,
            'entityDataKey' => $entityDataKey
        ]);
    }
}