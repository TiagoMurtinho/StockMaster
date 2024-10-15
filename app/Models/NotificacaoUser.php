<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notificacao()
    {
        return $this->belongsTo(Notificacao::class);
    }
}
