<?php

namespace App\Exceptions;

use Exception;

class EnderecoNaoInformadoException extends Exception
{
    public function __construct(string $message = "Endereço de entrega não informado.")
    {
        parent::__construct($message);
    }
}
