<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Standard success response
     */
    protected function success($data, string $message = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'status' => $code,
            'message' => $message,
            'data' => $data,
            'meta' => [
                'timestamp' => now()->toISOString(),
                'requestId' => request()->header('X-Request-ID', uniqid('req_')),
            ]
        ], $code);
    }

    /**
     * Standard error response
     */
    protected function error(string $message, int $code, $details = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'status' => $code,
            'message' => $message,
            'errors' => $details,
            'meta' => [
                'timestamp' => now()->toISOString(),
                'requestId' => request()->header('X-Request-ID', uniqid('req_')),
            ]
        ], $code);
    }
}
