<?php
class Validator
{
    protected $data;
    protected $errors = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function required($field, $message = null)
    {
        if (empty($this->data[$field])) {
            $this->errors[$field][] = $message ?? "$field is required.";
        }
    }

    public function email($field, $message = null)
    {
        if (!filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = $message ?? "Invalid email format.";
        }
    }

    public function min($field, $length, $message = null)
    {
        if (strlen($this->data[$field]) < $length) {
            $this->errors[$field][] = $message ?? "$field must be at least $length characters.";
        }
    }

    public function confirmed($field, $confirmationField, $message = null)
    {
        if ($this->data[$field] !== $this->data[$confirmationField]) {
            $this->errors[$field][] = $message ?? "$field does not match.";
        }
    }
    public function numeric($field, $message = null)
    {
        if (!is_numeric($this->data[$field])) {
            $this->errors[$field][] = $message ?? "$field must be a number.";
        }
    }

    public function regex($field, $pattern, $message = null)
    {
        if (!preg_match($pattern, $this->data[$field])) {
            $this->errors[$field][] = $message ?? "$field format is invalid.";
        }
    }

    public function date($field, $format = 'Y-m-d', $message = null)
    {
        $d = DateTime::createFromFormat($format, $this->data[$field]);
        if (!($d && $d->format($format) === $this->data[$field])) {
            $this->errors[$field][] = $message ?? "$field must be a valid date ($format).";
        }
    }

    public function in($field, array $values, $message = null)
    {
        if (!in_array($this->data[$field], $values)) {
            $this->errors[$field][] = $message ?? "$field must be one of: " . implode(', ', $values);
        }
    }

    public function unique($field, $table, $column, PDO $pdo, $message = null)
    {
        $value = $this->data[$field] ?? null;

        if ($value === null) {
            return; // skip if the field is not present (handled by required)
        }

        $sql = "SELECT COUNT(*) FROM $table WHERE $column = :value LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':value', $value);
        $stmt->execute();

        $exists = $stmt->fetchColumn();

        if ($exists) {
            $this->errors[$field][] = $message ?? "$field already exists.";
        }
    }



    public function errors()
    {
        return $this->errors;
    }

    public function passes()
    {
        return empty($this->errors);
    }
}
