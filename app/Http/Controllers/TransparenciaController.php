<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
class TransparenciaController extends Controller
{
    public function showList(){
        $users = Users::all();

        return view('transparencia.index', compact('users'));
    }
}
