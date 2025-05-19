<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
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
            if ($this->shouldReport($e)) {
                Log::error('Unhandled exception', [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        });

        // Handle database errors
        $this->renderable(function (QueryException $e, $request) {
            Log::error('Database error', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'A database error occurred. Please try again later.',
                ], 500);
            }

            return response()->view('errors.500', [
                'message' => 'A database error occurred. Please try again later.',
            ], 500);
        });

        // Handle validation errors
        $this->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        // Handle 404 errors
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Resource not found',
                ], 404);
            }

            return response()->view('errors.404', [], 404);
        });

        // Handle authentication errors
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Unauthenticated',
                ], 401);
            }

            return redirect()->guest(route('login'));
        });

        // Handle CSRF token mismatches
        $this->renderable(function (TokenMismatchException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'CSRF token mismatch',
                    'message' => 'Please refresh the page and try again.',
                ], 419);
            }

            return redirect()->back()
                ->withInput($request->except($this->dontFlash))
                ->with('error', 'Your session has expired. Please try again.');
        });
    }
} 