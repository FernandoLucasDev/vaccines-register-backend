<?php

namespace App\Services;

use InvalidArgumentException;

class EmailValidatorService 
{
    /**
     * Validate provided email.
     *
     * @param string $email
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function validate($email): bool
    {
        if (empty($email)) {
            throw new InvalidArgumentException('The email can not be empty.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email.');
        }

        return true;
    }
}