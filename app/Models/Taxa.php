<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Taxa extends Model
{
    use HasFactory;

    protected $table = 'taxa';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
        'valor',
        'user_id'
    ];

    public function linha_documento(): HasMany
    {
        return $this->hasMany(LinhaDocumento::class, 'taxa_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
