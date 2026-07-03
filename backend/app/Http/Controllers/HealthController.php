<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;

class HealthController extends Controller
{
    /**
     * Check the health status of the API.
     */
    public function __invoke(): JsonResponse
    {
        return ApiResponse::success(
            data: [
                'name' => config('app.name'),
                'version' => 'v1',
                'environment' => app()->environment(),
                'status' => 'UP',
                'timestamp' => now()->toISOString(),
            ],
            message: 'API is healthy.'
        );
    }
}
