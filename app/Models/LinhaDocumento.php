<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LinhaDocumento extends Model
{
    use HasFactory;

    protected $table = 'linha_documento';

    protected $primaryKey = 'id';

    protected $fillable = [
        'descricao',
        'valor',
        'morada',
        'data_entrega',
        'data_recolha',
        'extra',
        'documento_id',
        'artigo_id',
        'user_id'
    ];

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class, 'documento_id');
    }

    public function tipo_palete(): BelongsToMany
    {
        return $this->belongsToMany(TipoPalete::class, 'linha_documento_tipo_palete')
            ->withPivot('quantidade')
            ->withTimestamps();
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
