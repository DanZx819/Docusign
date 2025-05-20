<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $fillable = ['atividade_id', 'user_id', 'arquivo', 'nota'];

    public function atividade()
    {
        return $this->belongsTo(Atividade::class);
    }

    public function user()
    {
        return $this->belongsTo(Users::class);
    }
}
