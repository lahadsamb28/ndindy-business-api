<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => $this->getStatus($exception)
            ], $this->getStatus($exception));
        }

        return parent::render($request, $exception);
    }

    private function getStatus(Throwable $exception): int
    {
        if (method_exists($exception, 'getCode')) {
           return $exception->getCode() ?: 500;
        }
        return 500;
    }
}
