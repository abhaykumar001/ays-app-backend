<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

class RouteHelper
{
    /**
     * Get all frontend route names (filtered by namespace)
     *
     * @param string|null $namespace
     * @return array
     */
    public static function frontendRoutes(string $namespace = 'App\Http\Controllers\Frontend\\'): array
    {
        return collect(Route::getRoutes())
            ->filter(function ($route) use ($namespace) {
                $action = $route->getActionName();
                return $route->getName() // Only named routes
                    && str_starts_with($action, $namespace);
            })
            ->mapWithKeys(fn($route) => [$route->getName() => $route->getName()])
            ->toArray();
    }
}
