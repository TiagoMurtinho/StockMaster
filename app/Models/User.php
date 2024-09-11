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

    protected $table = 'user';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
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
        return $this->hasMany(Armazem::class, 'armazem_id', 'id');
    }

    public function artigo(): HasMany
    {
        return $this->hasMany(Artigo::class, 'artigo_id', 'id');
    }

    public function cliente(): HasMany
    {
        return $this->hasMany(Cliente::class, 'cliente_id', 'id');
    }

    public function documento(): HasMany
    {
        return $this->hasMany(Documento::class, 'documento_id', 'id');
    }

    public function linha_documento(): HasMany
    {
        return $this->hasMany(LinhaDocumento::class, 'linha_documento_id', 'id');
    }

    public function palete(): HasMany
    {
        return $this->hasMany(Palete::class, 'palete_id', 'id');
    }

    public function tipo_documento(): HasMany
    {
        return $this->hasMany(TipoDocumento::class, 'tipo_documento_id', 'id');
    }

    public function tipo_palete(): HasMany
    {
        return $this->hasMany(TipoPalete::class, 'tipo_palete_id', 'id');
    }
}
