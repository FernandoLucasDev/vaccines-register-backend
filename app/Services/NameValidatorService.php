<?php

namespace App\Services;

class NameValidatorService
{
    /**
     * Validate if the provided name is full name (first and last name).
     *
     * @param string $name
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function validate($name): bool 
    {
        return is_string($name) && str_word_count(trim($name)) > 1;
    }
}