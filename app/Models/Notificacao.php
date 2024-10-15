<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacao extends Model
{
    use HasFactory;

    protected $table = 'notificacao';

    protected $fillable = [
        'documento_id',
        'message',
        'is_read',
    ];

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'notificacao_user')
            ->withPivot('is_read')
            ->withTimestamps();
    }
}
