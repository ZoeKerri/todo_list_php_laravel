<?php

namespace App\DTOs;

class ApiResponse
{
    public static function success($data = null, string $message = 'Success', int $status = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public static function error(string $message = 'Error', $errors = null, int $status = 400)
    {
        $response = [
            'status' => $status,
            'message' => $message,
            'data' => null,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }

    public static function unauthorized(string $message = 'Unauthorized')
    {
        return self::error($message, null, 401);
    }

    public static function forbidden(string $message = 'Forbidden')
    {
        return self::error($message, null, 403);
    }

    public static function notFound(string $message = 'Resource not found')
    {
        return self::error($message, null, 404);
    }

    public static function serverError(string $message = 'Internal server error')
    {
        return self::error($message, null, 500);
    }
}
