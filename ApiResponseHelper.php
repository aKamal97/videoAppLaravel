<?php

namespace App\Http\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponseHelper
{
    /**
     * Return a success JSON response
     */
    public static function success($data = null, string $message = null, int $statusCode = 200): JsonResponse
    {
        $response = [
            'success' => true,
        ];

        if ($message !== null) {
            $response['message'] = $message;
        }

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return an error JSON response
     */
    public static function error(string $message, int $statusCode = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'error' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return validation error response
     */
    public static function validationError($errors, string $message = 'بيانات غير صحيحة'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => $message,
            'errors' => $errors,
        ], 422);
    }

    /**
     * Return not found error response
     */
    public static function notFound(string $message = 'العنصر غير موجود'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => $message,
        ], 404);
    }

    /**
     * Return unauthorized error response
     */
    public static function unauthorized(string $message = 'غير مصرح لك بالوصول'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => $message,
        ], 401);
    }

    /**
     * Return forbidden error response
     */
    public static function forbidden(string $message = 'ممنوع الوصول'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => $message,
        ], 403);
    }

    /**
     * Return server error response
     */
    public static function serverError(string $message = 'خطأ في الخادم'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => $message,
        ], 500);
    }

    /**
     * Return custom response
     */
    public static function custom(array $data, int $statusCode = 200): JsonResponse
    {
        return response()->json($data, $statusCode);
    }

    /**
     * Return paginated data response
     */
    public static function paginated($data, string $message = null): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ],
        ];

        if ($message !== null) {
            $response['message'] = $message;
        }

        return response()->json($response, 200);
    }

    /**
     * Return created resource response
     */
    public static function created($data = null, string $message = 'تم الإنشاء بنجاح'): JsonResponse
    {
        return self::success($data, $message, 201);
    }

    /**
     * Return updated resource response
     */
    public static function updated($data = null, string $message = 'تم التحديث بنجاح'): JsonResponse
    {
        return self::success($data, $message, 200);
    }

    /**
     * Return deleted resource response
     */
    public static function deleted(string $message = 'تم الحذف بنجاح'): JsonResponse
    {
        return self::success(null, $message, 200);
    }

    /**
     * Return no content response
     */
    public static function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }
}