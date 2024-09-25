<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoTipoPalete extends Model
{
    use HasFactory;

    protected $table = 'documento_tipo_palete';

    protected $fillable = [
        'documento_id',
        'tipo_palete_id',
        'artigo_id',
        'armazem_id',
        'quantidade',
        'localizacao'
    ];

    public function documento()
    {
        return $this->belongsTo(Documento::class);
    }

    public function tipo_palete()
    {
        return $this->belongsTo(TipoPalete::class);
    }

    public function artigo()
    {
        return $this->belongsTo(Artigo::class, 'artigo_id');
    }

    public function armazem()
    {
        return $this->belongsTo(Armazem::class);
    }
}
