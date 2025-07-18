<?php

namespace App\Services;

class FreteService
{
    public function calculaFrete(float $subtotal): float
    {
        if ($subtotal > 200) {
            return 0; // Frete grátis para valores acima de R$200,00
        } elseif ($subtotal >= 52 && $subtotal <= 166.59) {
            return 15; // R$15,00 para valores entre R$52,00 e R$166,59
        } else {
            return 20; // R$20,00 para outros valores
        }
    }
}
