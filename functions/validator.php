<?php

function validate_required($data, $field, &$errors, $message = null)
{
    if (empty($data[$field])) {
        $errors[$field][] = $message ?? "$field is required.";
    }
}

function validate_email($data, $field, &$errors, $message = null)
{
    if (!filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
        $errors[$field][] = $message ?? "Invalid email format.";
    }
}

function validate_min($data, $field, $length, &$errors, $message = null)
{
    if (strlen($data[$field]) < $length) {
        $errors[$field][] = $message ?? "$field must be at least $length characters.";
    }
}

function validate_confirmed($data, $field, $confirmField, &$errors, $message = null)
{
    if ($data[$field] !== $data[$confirmField]) {
        $errors[$field][] = $message ?? "$field does not match.";
    }
}

function validate_numeric($data, $field, &$errors, $message = null)
{
    if (!is_numeric($data[$field])) {
        $errors[$field][] = $message ?? "$field must be a number.";
    }
}

function validate_regex($data, $field, $pattern, &$errors, $message = null)
{
    if (!preg_match($pattern, $data[$field])) {
        $errors[$field][] = $message ?? "$field format is invalid.";
    }
}

function validate_date($data, $field, $format, &$errors, $message = null)
{
    $d = DateTime::createFromFormat($format, $data[$field]);
    if (!($d && $d->format($format) === $data[$field])) {
        $errors[$field][] = $message ?? "$field must be a valid date ($format).";
    }
}

function validate_in($data, $field, $values, &$errors, $message = null)
{
    if (!in_array($data[$field], $values)) {
        $errors[$field][] = $message ?? "$field must be one of: " . implode(', ', $values);
    }
}

function validate_unique($data, $field, $table, $column, PDO $pdo, &$errors, $excludeId = null, $excludeColumn = 'id', $message = null)
{
    $sql = "SELECT COUNT(*) FROM $table WHERE $column = :value";
    $params = ['value' => $data[$field]];

    if ($excludeId !== null) {
        $sql .= " AND $excludeColumn != :exclude_id";
        $params['exclude_id'] = $excludeId;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $errors[$field][] = $message ?? "$field already exists.";
    }
}
