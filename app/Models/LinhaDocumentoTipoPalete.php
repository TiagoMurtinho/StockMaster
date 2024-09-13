<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinhaDocumentoTipoPalete extends Model
{
    use HasFactory;

    protected $table = 'linha_documento_tipo_palete';

    protected $fillable = [
        'linha_documento_id',
        'tipo_palete_id',
        'quantidade',
    ];

    public function linha_documento()
    {
        return $this->belongsTo(LinhaDocumento::class);
    }

    public function tipo_palete()
    {
        return $this->belongsTo(TipoPalete::class);
    }
}
