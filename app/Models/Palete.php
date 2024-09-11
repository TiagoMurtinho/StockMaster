<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Palete extends Model
{
    use HasFactory;

    protected $table = 'palete';

    protected $primaryKey = 'id';

    protected $fillable = [
        'localizacao',
        'data_entrada',
        'data_saida'
    ];

    public function tipo_palete(): BelongsTo
    {
        return $this->belongsTo(TipoPalete::class, 'tipo_palete_id');
    }

    public function linha_documento(): BelongsTo
    {
        return $this->belongsTo(LinhaDocumento::class, 'linha_documento_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
