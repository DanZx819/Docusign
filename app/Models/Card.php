<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'title',
        'description',
        'filename',
        'turma_id',
    ];

    public function turma()
{
    return $this->belongsTo(Turma::class);
}

}
