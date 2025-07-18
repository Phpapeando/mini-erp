<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produto extends Model
{
    protected $fillable = ['nome', 'preco'];

    public function estoques(): HasMany
    {
        return $this->hasMany(Estoque::class);
    }
}
