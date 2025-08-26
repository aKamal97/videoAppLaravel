<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse as LaravelJsonResponse;

trait JsonResponse
{
    /**
     * Get current request instance.
     */
    private function getCurrentRequest()
    {
        return request();
    }

    /**
     * Return a standardized JSON response.
     *
     * @param array $data
     * @param string $message
     * @param int $statusCode
     * @param \Illuminate\Http\Request|null $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data = [], string $message = '', int $statusCode = 200, $request = null): LaravelJsonResponse
    {
        $request = $request ?: $this->getCurrentRequest();
        
        $response = [
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'url' => $request->fullUrl(),
            'timestamp' => now()->toISOString()
        ];

        return response()->json($response, $statusCode);
    }
    /**
     * Return a standardized error JSON response.
     *
     * @param string $message
     * @param int $statusCode
     * @param \Illuminate\Http\Request|null $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function failure(string $message, int $statusCode = 400, $request = null): LaravelJsonResponse
    {
        $request = $request ?: $this->getCurrentRequest();
        
        $response = [
            'status' => 'false',
            'error' => $message,
            'url' => $request->fullUrl(),
            'timestamp' => now()->toISOString()
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Return a standardized validation error JSON response.
     *
     * @param array $errors
     * @param string $message
     * @param int $statusCode
     * @param \Illuminate\Http\Request|null $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validationFailure(array $errors, string $message = null, int $statusCode = 422, $request = null): LaravelJsonResponse
    {
        $request = $request ?: $this->getCurrentRequest();
        
        $response = [
            'status' => 'false',
            'message' => $message ?? __('Validation failed'),
            'errors' => $errors,
            'url' => $request->fullUrl(),
            'timestamp' => now()->toISOString()
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Return a standardized not found JSON response.
     *
     * @param string $message
     * @param int $statusCode
     * @param \Illuminate\Http\Request|null $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function notFoundResponse(string $message = null, int $statusCode = 404, $request = null): LaravelJsonResponse
    {
        $request = $request ?: $this->getCurrentRequest();
        
        $response = [
            'status' => 'false',
            'error' => $message ?? __('Resource not found'),
            'url' => $request->fullUrl(),
            'timestamp' => now()->toISOString()
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Return a standardized unauthorized JSON response.
     *
     * @param string $message
     * @param int $statusCode
     * @param \Illuminate\Http\Request|null $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthorizedResponse(string $message = null, int $statusCode = 401, $request = null): LaravelJsonResponse
    {
        $request = $request ?: $this->getCurrentRequest();
        
        $response = [
            'status' => 'false',
            'error' => $message ?? __('Unauthorized'),
            'url' => $request->fullUrl(),
            'timestamp' => now()->toISOString()
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Return a standardized forbidden JSON response.
     *
     * @param string $message
     * @param int $statusCode
     * @param \Illuminate\Http\Request|null $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forbiddenResponse(string $message = null, int $statusCode = 403, $request = null): LaravelJsonResponse
    {
        $request = $request ?: $this->getCurrentRequest();
        
        $response = [
            'status' => 'false',
            'error' => $message ?? __('Forbidden'),
            'url' => $request->fullUrl(),
            'timestamp' => now()->toISOString()
        ];

        return response()->json($response, $statusCode);
    }
}