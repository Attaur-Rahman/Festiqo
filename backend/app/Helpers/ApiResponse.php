<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    // Returns a standardized JSON response for successful API requests.
    public static function success(
        mixed $data = null,
        string $message = 'Success',
        int $status = 200
    ): JsonResponse {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }

    // Returns a standardized JSON response for failed API requests.
    public static function error(
        string $message,
        int $status = 400,
        ?array $errors = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (! empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }
}
