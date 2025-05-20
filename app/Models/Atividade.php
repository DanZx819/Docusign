<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atividade extends Model
{
    public function criador()
{   
    
    return $this->belongsTo(Users::class, 'user_id');
}
protected $fillable = ['user_id', 'turma_id', 'titulo', 'descricao'];

}
