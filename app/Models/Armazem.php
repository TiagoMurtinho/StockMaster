<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Armazem extends Model
{
    use HasFactory;

    protected $table = 'armazem';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
        'capacidade'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function tipo_palete(): BelongsTo
    {
        return $this->belongsTo(TipoPalete::class, 'tipo_palete_id', 'id');
    }
}
