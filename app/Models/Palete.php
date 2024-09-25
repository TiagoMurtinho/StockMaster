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
        'data_saida',
        'tipo_palete_id',
        'documento_id',
        'user_id',
        'artigo_id',
        'armazem_id',
        'cliente_id'
    ];

    public function tipo_palete(): BelongsTo
    {
        return $this->belongsTo(TipoPalete::class, 'tipo_palete_id');
    }

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class, 'documento_id');
    }

    public function artigo(): BelongsTo
    {
        return $this->belongsTo(Artigo::class, 'artigo_id');
    }

    public function armazem(): BelongsTo
    {
        return $this->belongsTo(Armazem::class, 'armazem_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
