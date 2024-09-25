<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documento';

    protected $primaryKey = 'id';

    protected $fillable = [
        'estado',
        'observacao',
        'previsao',
        'numero',
        'data',
        'matricula',
        'morada',
        'hora_carga',
        'descarga',
        'data_entrada',
        'data_saida',
        'total',
        'extra',
        'taxa_id',
        'tipo_documento_id',
        'cliente_id',
        'user_id',
    ];

    public function tipo_documento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class,'tipo_documento_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class,'cliente_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function taxa(): BelongsTo
    {
        return $this->belongsTo(Taxa::class, 'taxa_id');
    }

    public function tipo_palete(): BelongsToMany
    {
        return $this->belongsToMany(TipoPalete::class, 'documento_tipo_palete')
            ->withPivot('quantidade', 'artigo_id', 'armazem_id', 'localizacao')
            ->withTimestamps();
    }
}
