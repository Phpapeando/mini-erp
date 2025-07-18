<?php

namespace App\Exceptions;

use Exception;

class CarrinhoVazioException extends Exception
{
    public function __construct(string $message = "Carrinho está vazio.")
    {
        parent::__construct($message);
    }
}
