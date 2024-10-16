<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificacaoUser extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'notificacao_user';

    protected $fillable = [
        'user_id',
        'notificacao_id',
        'is_read',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notificacao(): BelongsTo
    {
        return $this->belongsTo(Notificacao::class);
    }
}
