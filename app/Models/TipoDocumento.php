<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TipoDocumento extends Model
{
    use HasFactory;

    protected $table = 'tipo_documento';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nome'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function documento(): HasOne
    {
        return $this->hasOne(Documento::class, 'tipo_documento_id');
    }
}
