<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LinhaDocumento extends Model
{
    use HasFactory;

    protected $table = 'linha_documento';

    protected $primaryKey = 'id';

    protected $fillable = [
        'quantidade',
        'descricao',
        'valor',
        'morada',
        'data_entrega',
        'data_recolha',
        'extra'
    ];

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class, 'documento_id');
    }

    public function tipo_palete(): BelongsTo
    {
        return $this->belongsTo(TipoPalete::class, 'tipo_palete_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function palete(): HasMany
    {
        return $this->hasMany(Palete::class, 'linha_documento_id');
    }
}
