<?php

function send_json($data = [], int $statusCode = 200)
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function send_json_success($message = 'Success', $data = [], int $statusCode = 200)
{
    send_json([
        'status' => 'success',
        'message' => $message,
        'data' => $data
    ], $statusCode);
}

function send_json_error($message = 'Something went wrong', $errors = [], int $statusCode = 200)
{
    send_json([
        'status' => 'failed',
        'message' => $message,
        'errors' => $errors
    ], $statusCode);
}
