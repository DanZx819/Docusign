<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;
class ValidacaoController extends Controller
{
    public function show(){
        $files = Card::latest()->get();
        return view('validação.validacao', compact('files'));
    }
}
