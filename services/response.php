<?php
class Response
{
    public static function json($data = [], int $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function success($message = 'Success', $data = [], int $statusCode = 200)
    {
        self::json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public static function error($message = 'Something went wrong', $errors = [], int $statusCode = 400)
    {
        self::json([
            'status' => 'failed',
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }
}
