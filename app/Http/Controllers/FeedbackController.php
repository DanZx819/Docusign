<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    // Mostrar a lista de feedbacks
    public function index()
    {
        $feedbacks = Feedback::with('usuario')->latest()->get(); // Carrega quem enviou o feedback
        return view('feedbacks.index', compact('feedbacks'));
    }

    // Exibir o formulário de criação
    public function create()
    {
        return view('feedbacks.create');
    }

    // Salvar o feedback enviado
    public function store(Request $request)
{
    $request->validate([
        'titulo' => 'required|string|max:255',
        'descricao' => 'required|string',
        'avaliacao' => 'required|in:boa,neutra,ruim',
        'sugestoes' => 'nullable|string',
    ]);

    $user = \App\Models\Users::find(session('user_id'));

    if (!$user) {
        return redirect()->back()->withErrors('Você precisa estar logado para enviar um feedback.');
    }

    Feedback::create([
        'titulo' => $request->titulo,
        'descricao' => $request->descricao,
        'avaliacao' => $request->avaliacao,
        'usuario_id' => $user->id,
        'sugestoes' => $request->sugestoes,
    ]);

    return redirect()->route('feedbacks.index')->with('success', 'Feedback enviado com sucesso!');
}

}
