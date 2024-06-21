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
                    throw new Exception("Validation rule $rule does not exist.");
                }
            }
        }
        return empty($this->errors);
    }
}