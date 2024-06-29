<?php

namespace App\Core;

use Exception;

class Validator
{
    private array $errors = [];

    /**
     * @throws Exception
     */
    public function validate(array $data, array $rules): bool
    {
        foreach ($rules as $field => $ruleSet) {
            foreach ($ruleSet as $rule) {
                $method = 'validate' . ucfirst($rule);
                if (method_exists($this, $method)) {
                    $this->$method($field, $data[$field] ?? null);
                } else {
                    throw new Exception("Правило валидации $rule не существует.");
                }
            }
        }
        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function validateRequired(string $field, $value): void
    {
        if (empty($value)) {
            $this->errors[$field][] = 'Поле ' . $field . ' обязательно для заполнения.';
        }
    }

    private function validateEmail(string $field, $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = 'Поле ' . $field . ' должно быть действительным адресом электронной почты.';
        }
    }

    private function validateMinLength(string $field, $value, int $min): void
    {
        if (strlen($value) < $min) {
            $this->errors[$field][] = 'Поле ' . $field . ' должно быть не менее ' . $min . ' символов.';
        }
    }
}
