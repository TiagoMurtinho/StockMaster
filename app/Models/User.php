<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'contacto',
        'salario',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function armazem(): HasMany
    {
        return $this->hasMany(Armazem::class, 'user_id');
    }

    public function artigo(): HasMany
    {
        return $this->hasMany(Artigo::class, 'user_id');
    }

    public function cliente(): HasMany
    {
        return $this->hasMany(Cliente::class, 'user_id');
    }

    public function documento(): HasMany
    {
        return $this->hasMany(Documento::class, 'user_id');
    }

    public function palete(): HasMany
    {
        return $this->hasMany(Palete::class, 'user_id');
    }

    public function tipo_documento(): HasMany
    {
        return $this->hasMany(TipoDocumento::class, 'user_id');
    }

    public function tipo_palete(): HasMany
    {
        return $this->hasMany(TipoPalete::class, 'user_id');
    }

    public function taxa(): HasMany
    {
        return $this->hasMany(Taxa::class, 'user_id');
    }

    public function notificacao()
    {
        return $this->belongsToMany(Notificacao::class, 'notificacao_user')
            ->withPivot('is_read')
            ->withTimestamps();
    }
}
