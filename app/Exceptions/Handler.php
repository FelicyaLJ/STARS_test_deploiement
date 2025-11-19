<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    protected $levels = [];
    protected $dontReport = [];
    protected $dontFlash = ['current_password', 'password', 'password_confirmation'];

    public function register(): void
    {
        //
    }

    public function render($request, Throwable $e)
    {
        $status = method_exists($e, 'getStatusCode')
            ? $e->getStatusCode()
            : ($e instanceof \Illuminate\Validation\ValidationException ? 422 : 500);

        if (view()->exists("errors.$status")) {
            return response()->view("errors.$status", [
                'title' => "$status Error",
                'code' => $status,
                'message' => $e->getMessage() ?: __('Une erreur est survenue.'),
            ], $status);
        }

        return response()->view("errors.fallback", [
            'title' => "$status Error",
            'code' => $status,
            'message' => $e->getMessage() ?: __('Une erreur est survenue.'),
        ], $status);
    }


    protected function unauthenticated($request, \Illuminate\Auth\AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return response()->view('errors.401', [
            'code' => 401,
            'message' => "Vous devez être connecté pour accéder à cette page."
        ], 401);
    }
}

