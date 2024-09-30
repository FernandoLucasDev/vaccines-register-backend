<?php

namespace App\Services;

class CpfFormatService
{
    /**
     * Format CPF string.
     *
     * @param string $cpf
     * @throws \InvalidArgumentException
     * @return string
     */
    public function format($cpf)
    {
        return substr($cpf, 0, 3) . '.***.***-**';
    }
}
