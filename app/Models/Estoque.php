<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Estoque extends Model
{
    protected $fillable = ['produto_id', 'variacao', 'quantidade'];

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }
}
