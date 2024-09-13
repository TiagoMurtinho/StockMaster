<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documento';

    protected $primaryKey = 'id';

    protected $fillable = [
        'numero',
        'data',
        'matricula',
        'morada',
        'hora_carga',
        'descarga',
        'total',
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

    public function linha_documento(): HasMany
    {
        return $this->hasMany(LinhaDocumento::class, 'documento_id');
    }
}
