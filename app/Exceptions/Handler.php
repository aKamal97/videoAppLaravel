<?php

namespace App\Exceptions;


use App\Traits\JsonResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use JsonResponse;

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Customize the response for specific exceptions.
     */
    public function render($request, Throwable $exception)
    {
        // Always handle API routes with JSON responses, regardless of debug mode
        if ($this->isApiRequest($request)) {
            return $this->handleApiExceptions($request, $exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Determine if the request is an API request that should return JSON.
     */
    private function isApiRequest($request): bool
    {
        return $request->is('api/*') ||
               $request->wantsJson() ||
               $request->expectsJson() ||
               $request->ajax() ||
               str_contains($request->header('Accept', ''), 'application/json');
    }

    /**
     * Handle API exceptions and return JSON responses.
     */
    private function handleApiExceptions($request, Throwable $exception)
    {
        // Handle specific exceptions in order of specificity
        return match (true) {
            // Method Not Allowed Exception (405)
            $exception instanceof MethodNotAllowedHttpException => $this->handleMethodNotAllowed($exception, $request),

            // Not Found Exception (404)
            $exception instanceof NotFoundHttpException => $this->notFoundResponse(__('Endpoint not found'), 404, $request),

            // Validation Exception (422)
            $exception instanceof ValidationException => $this->validationFailure(
                $exception->errors(),
                __('Validation failed'),
                422,
                $request
            ),

            // Model Not Found Exception (404)
            $exception instanceof ModelNotFoundException => $this->notFoundResponse(__('Resource not found'), 404, $request),

            // Authentication Exception (401)
            $exception instanceof AuthenticationException => $this->unauthorizedResponse(__('Authentication required'), 401, $request),

            // Authorization Exception (403)
            $exception instanceof AuthorizationException => $this->forbiddenResponse(__('You are not authorized to perform this action'), 403, $request),

            // Generic HTTP Exceptions
            $exception instanceof HttpException => $this->handleHttpException($exception, $request),

            // Generic server error (500)
            default => $this->handleGenericException($exception, $request)
        };
    }

    /**
     * Handle Method Not Allowed HTTP Exception.
     */
    private function handleMethodNotAllowed(MethodNotAllowedHttpException $exception, $request): \Illuminate\Http\JsonResponse
    {
        $headers = $exception->getHeaders();
        $allowedMethods = isset($headers['Allow'])
            ? (is_array($headers['Allow']) ? implode(', ', $headers['Allow']) : $headers['Allow'])
            : '';
        $currentMethod = $request->getMethod();

        $message = $allowedMethods
            ? __('HTTP method :method is not allowed. Allowed methods: :methods', [
                'method' => $currentMethod,
                'methods' => $allowedMethods
            ])
            : __('HTTP method :method is not allowed for this route', ['method' => $currentMethod]);

        return $this->failure($message, 405, $request);
    }

    /**
     * Handle generic HTTP exceptions.
     */
    private function handleHttpException(HttpException $exception, $request): \Illuminate\Http\JsonResponse
    {
        $statusCode = $exception->getStatusCode();
        $message = $exception->getMessage() ?: __('An error occurred');

        return match ($statusCode) {
            401 => $this->unauthorizedResponse($message, 401, $request),
            403 => $this->forbiddenResponse($message, 403, $request),
            404 => $this->notFoundResponse($message, 404, $request),
            422 => $this->validationFailure([], $message, 422, $request),
            default => $this->failure($message, $statusCode, $request)
        };
    }

    /**
     * Handle generic exceptions.
     */
    private function handleGenericException(Throwable $exception, $request): \Illuminate\Http\JsonResponse
    {
        $message = app()->environment('production')
            ? __('Internal server error')
            : $exception->getMessage();

        return $this->failure($message, 500, $request);
    }
}
