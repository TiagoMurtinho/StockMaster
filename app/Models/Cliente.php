<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
        'morada',
        'nif'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function documento(): HasMany
    {
        return $this->hasMany(Documento::class,'cliente_id');
    }

    public function artigo(): HasMany
    {
        return $this->hasMany(Artigo::class, 'cliente_id');
    }
}
