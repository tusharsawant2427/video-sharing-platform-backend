<?php


namespace App\Helpers\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class ResponseHelper
{
    public const HTTP_TOKEN_EXPIRED = 498;

    public const DEFAULT_SUCCESS_MESSAGE = "Successfully fetched data";
    public const DEFAULT_INTERNAL_ERROR_FMT = "Some internal server error occurred. %s";
    public const DEFAULT_FORBIDDEN_FMT = "Cannot access the given route. %s";
    public const DEFAULT_SERVICE_UNAVAILABLE = "Service is unavailable. %s";
    public const BAD_REQUEST_MESSAGE = "Bad request";
    public const SUCCESSFULLY_CREATED_MESSAGE = "Successfully Created";
    public const SUCCESSFULLY_DELETED_MESSAGE = "Successfully Deleted";
    public const SUCCESSFULLY_UPDATED_MESSAGE = "Successfully Updated";
    public const UNPROCESSABLE_ENTITY_MESSAGE = "Could not process data";
    public const TOKEN_EXPIRED_MESSAGE = "User's token expired";
    public const LOCKED_MESSAGE = "You already are logged in other device";
    public const UNAUTHORIZED_MESSAGE = "Unauthenticated";
    public const ARGUMENT_COUNT_MESSAGE = "Parameter's are missing";
    public const ALREADY_EXISTS_MESSAGE = "record already exists";
    public const OUT_OF_STOCK_MESSAGE = "Out Of Stock";
    public const PRODUCT_MINIMUM_QUANTITY_MESSAGE = "Product minimum quantity";
    public const PRODUCT_MAXIMUM_QUANTITY_EXCEPTION_MESSAGE = "Product maximum quantity";
    public const PRODUCT_STATUS_IN_ACTIVE_EXCEPTION_MESSAGE = "Product is in-active";
    public const CART_EXCEPTION_MESSAGE = "Cart Error";
    public const INVALID_OTP_EXCEPTION = "Invalid Otp";
    public const OTP_EXPIRED_EXCEPTION = "Otp IS Expired";
    public const COUPON_EXCEPTION = "Invalid Coupon";
    public const TOO_MANY_ATTEMPTS = "Too Many Attempts";

    /**
     * @param string $message
     *
     * @return JsonResponse
     */
    public static function notFound(string $message): JsonResponse
    {
        return response()->json([
            "status" => "error",
            "message" => $message
        ], Response::HTTP_NOT_FOUND);
    }

    /**
     * @param string|null $details
     *
     * @return JsonResponse
     */
    public static function forbidden(string $details = null): JsonResponse
    {
        return response()->json([
            "status" => "error",
            "message" => sprintf(self::DEFAULT_FORBIDDEN_FMT, $details)
        ], Response::HTTP_FORBIDDEN);
    }


    /**
     * @param mixed|null $error
     *
     * @return JsonResponse
     */
    public static function badRequest(mixed $error = null): JsonResponse
    {
        if ($error) {
            return response()->json([
                "status" => "error",
                "message" => self::BAD_REQUEST_MESSAGE,
                "error" => $error
            ], Response::HTTP_BAD_REQUEST);
        }
        return response()->json([
            "status" => "error",
            "message" => self::BAD_REQUEST_MESSAGE,
            "error" => $error
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param mixed|null $details
     *
     * @return JsonResponse
     */
    public static function created(mixed $details = null): JsonResponse
    {
        return response()->json([
            "status" => "success",
            "message" => self::SUCCESSFULLY_CREATED_MESSAGE,
            "data" => $details
        ], Response::HTTP_CREATED);
    }

    /**
     * @param mixed|null $details
     *
     * @return JsonResponse
     */
    public static function deleted(mixed $details = null): JsonResponse
    {
        return response()->json([
            "status" => "success",
            "message" => self::SUCCESSFULLY_DELETED_MESSAGE,
            "data" => $details
        ], Response::HTTP_OK);
    }

    /**
     * @param mixed|null $details
     *
     * @return JsonResponse
     */
    public static function updated(mixed $details = null): JsonResponse
    {
        if ($details) {
            return response()->json([
                "status" => "success",
                "message" => self::SUCCESSFULLY_UPDATED_MESSAGE,
                "data" => $details
            ], Response::HTTP_OK);
        }
        return response()->json([
            "status" => "success",
            "message" => self::SUCCESSFULLY_UPDATED_MESSAGE,
        ], Response::HTTP_OK);
    }

    /**
     * @param mixed|null $data
     * @param string $message
     *
     * @return JsonResponse
     */
    public static function success(mixed $data = null, string $message = self::DEFAULT_SUCCESS_MESSAGE, mixed $seoData = null): JsonResponse
    {
        return response()->json([
            "status" => "success",
            "message" => $message,
            "data" => $data,
        ], Response::HTTP_OK);
    }

    /**
     * @param mixed|null $data
     *
     * @return JsonResponse
     */
    public static function unprocessableEntity(mixed $data = null): JsonResponse
    {
        return response()->json([
            "status" => "error",
            "message" => self::UNPROCESSABLE_ENTITY_MESSAGE,
            "data" => $data
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param mixed|null $data
     *
     * @return JsonResponse
     */
    public static function tokenExpired(mixed $data = null): JsonResponse
    {
        return response()->json([
            "status" => "error",
            "message" => self::TOKEN_EXPIRED_MESSAGE,
            "data" => $data
        ], self::HTTP_TOKEN_EXPIRED);
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public static function isApiCall(Request $request): bool
    {
        return $request->is('api/*');
    }

    /**
     * @param mixed|null $data
     * @param mixed $message=self::UNAUTHORIZED_MESSAGE
     *
     * @return JsonResponse
     */
    public static function unauthorized(mixed $data = null, $message = self::UNAUTHORIZED_MESSAGE): JsonResponse
    {
        return response()->json([
            "status" => "error",
            "message" => $message,
            "data" => $data
        ], Response::HTTP_UNAUTHORIZED);
    }


    /**
     * @param mixed $message
     *
     * @return JsonResponse
     */
    public static function error(mixed $message): JsonResponse
    {
        return response()->json([
            "status" => "error",
            "message" => $message
        ], Response::HTTP_LOCKED);
    }

    /**
     * @param mixed|null $details
     *
     * @return JsonResponse
     */
    /**
     * @param mixed|null $details
     *
     * @return JsonResponse
     */
    public static function internalError(mixed  $details = null, int $code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        if ($details) {
            return response()->json([
                "status" => "error",
                "message" => $details,
                "error" => sprintf(self::DEFAULT_INTERNAL_ERROR_FMT, $details)
            ], $code);
        }
        return response()->json([
            "status" => "error",
            "message" => sprintf(self::DEFAULT_INTERNAL_ERROR_FMT, $details),
        ], $code);
    }


    /**
     * @param mixed|null $details
     *
     * @return JsonResponse
     */
    public static function argumentCountError(mixed $details = null): JsonResponse
    {
        if ($details) {
            return response()->json([
                "status" => "error",
                "message" => self::ARGUMENT_COUNT_MESSAGE,
                "error" => $details
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json([
            "status" => "error",
            "message" => self::ARGUMENT_COUNT_MESSAGE,
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param mixed $message
     *
     * @return JsonResponse
     */
    public static function productOutOfStock(mixed $message): JsonResponse
    {
        return response()->json([
            "status" => self::OUT_OF_STOCK_MESSAGE,
            "message" => $message
        ], Response::HTTP_LOCKED);
    }

    /**
     * @param mixed $message
     *
     * @return JsonResponse
     */
    public static function productMinimumQuantity(mixed $message): JsonResponse
    {
        return response()->json([
            "status" => self::PRODUCT_MINIMUM_QUANTITY_MESSAGE,
            "message" => $message
        ], Response::HTTP_LOCKED);
    }

    /**
     * @param mixed $message
     *
     * @return JsonResponse
     */
    public static function productMaximumQuantity(mixed $message): JsonResponse
    {
        return response()->json([
            "status" => self::PRODUCT_MAXIMUM_QUANTITY_EXCEPTION_MESSAGE,
            "message" => $message
        ], Response::HTTP_LOCKED);
    }

    /**
     * @param mixed $message
     *
     * @return JsonResponse
     */
    public static function productInactive(mixed $message): JsonResponse
    {
        return response()->json([
            "status" => self::PRODUCT_STATUS_IN_ACTIVE_EXCEPTION_MESSAGE,
            "message" => $message
        ], Response::HTTP_LOCKED);
    }

    /**
     * @param mixed $message
     *
     * @return JsonResponse
     */
    public static function cartError(mixed $message): JsonResponse
    {
        return response()->json([
            "status" => self::CART_EXCEPTION_MESSAGE,
            "message" => $message
        ], Response::HTTP_LOCKED);
    }


    /**
     * @param mixed|null $details
     *
     * @return JsonResponse
     */
    public static function alreadyExistError(mixed $details = null): JsonResponse
    {
        return response()->json([
            "status" => "error",
            "message" => self::ALREADY_EXISTS_MESSAGE,
            "error" => $details
        ], Response::HTTP_CONFLICT);
    }


    /**
     * @param mixed|null $details
     *
     * @return JsonResponse
     */
    public static function invalidOtp(mixed $details = null): JsonResponse
    {
        return response()->json([
            "status" => "error",
            "message" => self::INVALID_OTP_EXCEPTION,
            "error" => $details
        ], Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @param mixed|null $details
     *
     * @return JsonResponse
     */
    public static function expiredOtp(mixed $details = null): JsonResponse
    {
        return response()->json([
            "status" => "error",
            "message" => self::OTP_EXPIRED_EXCEPTION,
            "error" => $details
        ], Response::HTTP_NOT_ACCEPTABLE);
    }


    /**
     * @param mixed|null $details
     *
     * @return JsonResponse
     */
    public static function couponException(mixed $details = null): JsonResponse
    {
        return response()->json([
            "status" => "error",
            "message" => self::COUPON_EXCEPTION,
            "error" => $details
        ], Response::HTTP_NOT_ACCEPTABLE);
    }



    /**
     * @param mixed|null $details
     *
     * @return JsonResponse
     */
    public static function tooManyAttemptsException(mixed $details = null): JsonResponse
    {
        return response()->json([
            "status" => "error",
            "message" => self::TOO_MANY_ATTEMPTS,
            "error" => $details
        ], Response::HTTP_TOO_MANY_REQUESTS);
    }



    // public static function returnPaginateResponse(LengthAwarePaginator $collection): JsonResponse
    // {
    //     return response()->json([
    //         "status" => "error",
    //         "message" => self::TOO_MANY_ATTEMPTS,
    //         "error" => $details
    //     ], Response::HTTP_TOO_MANY_REQUESTS);
    // }

}
