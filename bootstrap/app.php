<?php

use App\Helpers\Services\ResponseHelper;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->renderable(function (ValidationException $exception, Request $request) {
            if (ResponseHelper::isApiCall($request)) {
                return ResponseHelper::badRequest($exception->errors());
            }
        });

        $exceptions->renderable(function (InvalidArgumentException $exception, Request $request) {

            if (ResponseHelper::isApiCall($request)) {
                return ResponseHelper::unprocessableEntity($exception->getMessage());
            }
        });

        $exceptions->renderable(function (ErrorException $exception, Request $request) {

            if (ResponseHelper::isApiCall($request)) {
                return ResponseHelper::internalError($exception->getMessage());
            }
        });

        $exceptions->renderable(function (ModelNotFoundException $exception, Request $request) {

            if (ResponseHelper::isApiCall($request)) {
                return ResponseHelper::notFound($exception->getMessage());
            }
        });

        $exceptions->renderable(function (JWTException $exception, Request $request) {
            if (ResponseHelper::isApiCall($request)) {
                return ResponseHelper::tokenExpired($exception->getMessage());
            }
        });

        $exceptions->renderable(function (BadMethodCallException $exception, Request $request) {

            if (ResponseHelper::isApiCall($request)) {
                return ResponseHelper::unprocessableEntity($exception->getMessage());
            }
        });

        $exceptions->renderable(function (ArgumentCountError $exception, Request $request) {

            if (ResponseHelper::isApiCall($request)) {
                return ResponseHelper::argumentCountError($exception->getMessage());
            }
        });

        $exceptions->renderable(function (NotFoundHttpException $exception, Request $request) {

            if (ResponseHelper::isApiCall($request)) {
                if ($exception->getPrevious() instanceof ModelNotFoundException) {


                    $modelNotFoundException = $exception->getPrevious();
                    $modelName = class_basename($modelNotFoundException->getModel());

                    return ResponseHelper::notFound($modelName . " Not Found");
                }
                return ResponseHelper::notFound($exception->getMessage());
            }
        });


        $exceptions->renderable(function (AuthenticationException $exception, Request $request) {

            if (ResponseHelper::isApiCall($request)) {
                return ResponseHelper::unauthorized(message: "Unauthorized! Your not allowed to access");
            }
        });

        $exceptions->renderable(function (ThrottleRequestsException $exception, Request $request) {

            if (ResponseHelper::isApiCall($request)) {
                return ResponseHelper::tooManyAttemptsException($exception->getMessage());
            }
        });


        $exceptions->renderable(function (UniqueConstraintViolationException  $exception, Request $request) {

            if (ResponseHelper::isApiCall($request)) {
                return ResponseHelper::unprocessableEntity("Record already exists");
            }
        });

        $exceptions->renderable(function (Exception $exception, Request $request) {
            if (ResponseHelper::isApiCall($request)) {
                return ResponseHelper::internalError($exception->getMessage());
            }
        });

        $exceptions->renderable(function (Throwable $exception, Request $request) {

            if (ResponseHelper::isApiCall($request)) {
                return ResponseHelper::internalError($exception->getMessage());
            }
        });

        $exceptions->renderable(function (NotFoundHttpException $exception, Request $request) {

            if (ResponseHelper::isApiCall($request)) {
                return ResponseHelper::internalError($exception->getMessage());
            }
        });
    })->create();
