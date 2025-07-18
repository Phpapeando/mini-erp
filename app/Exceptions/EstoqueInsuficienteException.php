<?php

namespace App\Exceptions;

use Exception;

class EstoqueInsuficienteException extends Exception
{
    public function __construct(string $produto, string $variacao, int $disponivel)
    {
        $message = "Estoque insuficiente para {$produto} - {$variacao}. Disponível: {$disponivel}";
        parent::__construct($message);
    }
}
