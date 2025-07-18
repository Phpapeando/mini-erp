<?php

namespace App\Contracts;

interface CepServiceInterface
{
    public function buscarCep(string $cep): ?array;

    public function validarCep(string $cep): bool;
}
