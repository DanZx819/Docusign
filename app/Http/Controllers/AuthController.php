<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Users; 
class AuthController extends Controller
{
    public function showLogin(){
        return view('auth.login');
    }
    
    public function login(Request $request){
        $credenciais = $request->only(['email', 'password']);

        $user = Users::where('email', $credenciais['email'])->first();

        if($user && Hash::check($credenciais['password'], $user->password)){
            session(['user_id' => $user->id]);
            return redirect('docusign');
        }

        return back()->withErrors(['email' => 'Credenciais inválidas.']);
    }

    
    public function logout()
    {
        session()->forget('user_id');
        return redirect('/login');
    }
}
