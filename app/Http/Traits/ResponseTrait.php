<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{
    public function successResponse($data, $message = null): JsonResponse
    {
        return response()->json(
            [
                'success' => true,
                'errorCode' => null,
                'message' => $message,
                'data' => $data
            ],
            Response::HTTP_OK
        );
    }

    public function errorResponse($errorMessage, $errorCode): JsonResponse
    {
        return response()->json(
            [
                'success' => false,
                'errorCode' => $errorCode,
                'message' => $errorMessage,
                'data' => []
            ],
            $errorCode
        );
    }

    public function notFoundResponse($message = 'Not found', $errorCode = 'NF404'): JsonResponse
    {
        return response()->json(
            [
                'success' => false,
                'errorCode' => $errorCode,
                'message' => $message,
                'data' => [],
            ],
            Response::HTTP_NOT_FOUND
        );
    }

    public function unauthorizedResponse($message = 'Unauthorized', $errorCode = 'ER401'): JsonResponse
    {
        return response()->json(
            [
                'success' => false,
                'errorCode' => $errorCode,
                'message' => $message,
                'data' => [],
            ],
            Response::HTTP_UNAUTHORIZED
        );
    }
}
