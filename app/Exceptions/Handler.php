<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

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
     *
     * Wraps every API response so raw exception messages (SQL errors,
     * stack traces, credentials) never reach the mobile client. Real
     * detail still goes to storage/logs for us to debug.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $e, Request $request) {
            if (!$request->is('api/*') && !$request->expectsJson()) {
                return null; // Let the default web handler render.
            }
            return $this->renderApi($e);
        });
    }

    protected function renderApi(Throwable $e)
    {
        // Framework-native exceptions map cleanly to HTTP.
        if ($e instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => 'Certaines informations sont invalides.',
                'errors'  => $e->errors(),
            ], 422);
        }

        if ($e instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => 'Session expirée. Reconnectez-vous.',
            ], 401);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Ressource introuvable.',
            ], 404);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Méthode non autorisée.',
            ], 405);
        }

        if ($e instanceof TooManyRequestsHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Trop de requêtes. Réessayez dans un instant.',
            ], 429);
        }

        // A DB failure with a raw SQLSTATE message must NEVER reach the app.
        if ($e instanceof QueryException) {
            Log::error('DB error', [
                'sql'  => $e->getSql(),
                'code' => $e->getCode(),
                'msg'  => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur. Réessayez dans un instant.',
            ], 500);
        }

        // Symfony HTTP exceptions carry a status code — respect it, still
        // sanitize the message.
        if ($e instanceof HttpExceptionInterface) {
            $status = $e->getStatusCode();
            $client = $e->getMessage() !== '' && $status < 500
                ? $e->getMessage()
                : 'Erreur serveur. Réessayez dans un instant.';
            return response()->json([
                'success' => false,
                'message' => $client,
            ], $status);
        }

        // Everything else — log full detail, return generic message.
        Log::error('Unhandled API exception', [
            'type'  => get_class($e),
            'msg'   => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        $payload = [
            'success' => false,
            'message' => 'Erreur serveur. Réessayez dans un instant.',
        ];
        // In debug mode surface the class + message to speed up dev, but
        // never the trace.
        if (config('app.debug')) {
            $payload['debug'] = [
                'type' => class_basename($e),
                'msg'  => $e->getMessage(),
            ];
        }
        return response()->json($payload, 500);
    }
}
