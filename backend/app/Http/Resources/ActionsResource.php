<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class ActionsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $actionName = $this->resource[0];
        $actionRoute = route($this->resource[1], $this->resource[2] ?? [], false);
        $actionMethod = $this->getRouteMethod($this->resource[1]);
        $additionalData = $this->resource[3] ?? null;

        $response = [
            $actionName => [
                'url' => $actionRoute,
                'method' => $actionMethod,
            ]
        ];

        if (!empty($additionalData)) {
            $response[array_key_first($additionalData)] = array_values($additionalData)[0];
        }

        return $response;
    }

    /**
     * @param mixed $resource
     * 
     * @return object
     */
    public static function collection($resource): object
    {
        $new = [];

        foreach ($resource as $action) {
            $actionResource = new ActionsResource($action);
            $actionArray = $actionResource->toArray(request());

            foreach ($actionArray as $key => $value) {
                $new[$key] = $value;
            }
        }

        return (object) $new;
    }

    /**
     * @param string $routeName
     * 
     * @return string
     */
    protected function getRouteMethod(string $routeName): string
    {
        $route = Route::getRoutes()->getByName($routeName);
        return $route->methods()[0];
    }
}
