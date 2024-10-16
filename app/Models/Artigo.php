<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Artigo extends Model
{
    use HasFactory;

    protected $table = 'artigo';

    protected $primaryKey = 'id';

    protected $fillable = [
        'referencia',
        'nome'
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function palete(): HasMany
    {
        return $this->hasMany(Palete::class, 'artigo_id');
    }
}
