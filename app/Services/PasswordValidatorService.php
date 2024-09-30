<?php

namespace App\Services;

use InvalidArgumentException;

class PasswordValidatorService
{
    /**
     * Validate provided password.
     *
     * @param string $password
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function validate($password): bool
    {
        if (empty($password) || !is_string($password)) {
            throw new InvalidArgumentException('Password can not be empty.');
        }

        if(!$this->isValidPassword($password)) {
            throw new InvalidArgumentException('Invalid password.');
        }

        return true;
    }

    /**
     * Process and validate password.
     *
     * @param string $password
     * @return bool
     */
    private function isValidPassword($password): bool
    {
        if (strlen($password) < 6) {
            throw new InvalidArgumentException('Password must have at the least 6 chracters.');
        }

        if (!preg_match('/[A-Za-z]/', $password) || 
            !preg_match('/[0-9]/', $password) || 
            !preg_match('/[\W_]/', $password)) {
            throw new InvalidArgumentException('Password must have letters, numbers and symbols.');
        }

        return true;
    }
}