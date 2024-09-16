<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TipoPalete extends Model
{
    use HasFactory;

    protected $table = 'tipo_palete';

    protected $primaryKey = 'id';

    protected $fillable = [
        'tipo',
        'valor'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function linha_documento(): HasMany
    {
        return $this->belongsToMany(LinhaDocumento::class, 'linha_documento_tipo_palete')
            ->withPivot('quantidade', 'artigo_id')
            ->withTimestamps();
    }

    public function palete(): HasMany
    {
        return $this->hasMany(Palete::class, 'tipo_palete_id');
    }

    public function armazem(): HasOne
    {
        return $this->hasOne(Armazem::class, 'tipo_palete_id');
    }
}
