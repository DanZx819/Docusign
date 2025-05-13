<?php

// app/Models/Turma.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    protected $fillable = ['nome'];

    public function users()
    {
        return $this->hasMany(Users::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }
}
