<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'instituicao',
    ];


public function turma()
{
    return $this->belongsTo(Turma::class);
}

public function atividadesCriadas()
{
    return $this->hasMany(Atividade::class);
}

public function atividadesDaTurma()
{
    return Atividade::where('turma_id', $this->turma_id)->get();
}


}
