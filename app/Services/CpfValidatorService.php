<?php

namespace App\Services;

use App\Models\Employees;
use Exception;
use Illuminate\Support\Facades\Log;

class CpfValidatorService
{
    /**
     * Validate provided CPF.
     *
     * @param string $cpf
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function validate($cpf): bool
    {
        if (empty($cpf) || !is_string($cpf)) {
            throw new \InvalidArgumentException('CPF can not be empty.');
        }

        if ($this->cpfExists($cpf)) {
            throw new \InvalidArgumentException('CPF already registered.');
        }

        if (!$this->isValidCpf($cpf)) {
            throw new \InvalidArgumentException('Invalid CPF.');
        }
        
        return true;
    }

    /**
     * Process and validate CPF.
     *
     * @param string $cpf
     * @return bool
     */
    private function isValidCpf($cpf): bool
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += intval($cpf[$i]) * (10 - $i);
        }

        $rest = $sum % 11;
        $firstDigit = ($rest < 2) ? 0 : 11 - $rest;

        if ($firstDigit != intval($cpf[9])) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += intval($cpf[$i]) * (11 - $i);
        }
        $rest = $sum % 11;
        $secondDigit = ($rest < 2) ? 0 : 11 - $rest;

        if ($secondDigit != intval($cpf[10])) {
            return false;
        }

        return true;
    }

    private function cpfExists($cpf): bool
    {

        try{

            $hashedCpf = Employees::hashCpf($cpf);
            return Employees::where('cpf', $hashedCpf)->exists();
            
        } catch(Exception $e) {
            Log::error('Error checking if CPF exists: ' . $e->getMessage());
            return false;
        }
    }
}