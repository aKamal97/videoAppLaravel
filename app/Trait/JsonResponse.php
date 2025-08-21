<?php

namespace App\Trait;

use Illuminate\Http\JsonResponse as LaravelJsonResponse;

trait JsonResponse
{
    /**
     * Return a standardized JSON response.
     *
     * @param array $data
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function success(array $data = [], int $statusCode = 200): LaravelJsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], $statusCode);
    }
    /**
     * Return a standardized error JSON response.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     * */
    public function failuer(string $message, int $statusCode = 400): LaravelJsonResponse
    {
        return response()->json([
            'status' => 'false',
            'error' => $message
        ], $statusCode);
    }
}
