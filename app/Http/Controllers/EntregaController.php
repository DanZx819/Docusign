<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrega;
use App\Models\Users;
use App\Models\Atividade;
use Illuminate\Support\Facades\Storage;

class EntregaController extends Controller
{
    // Aluno entrega uma atividade
    public function store(Request $request)
    {
        $request->validate([
            'atividade_id' => 'required|exists:atividades,id',
            'arquivo' => 'nullable|file|max:2048',
        ]);

        $user = Users::find(session('user_id'));

        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuário não autenticado.');
        }

        $arquivoPath = null;
        if ($request->hasFile('arquivo')) {
            $arquivoPath = $request->file('arquivo')->store('entregas', 'public');
        }

        Entrega::create([
            'atividade_id' => $request->atividade_id,
            'user_id' => $user->id,
            'arquivo' => $arquivoPath,
        ]);

        return back()->with('success', 'Atividade entregue com sucesso.');
    }

    // Admin visualiza todas as entregas
    public function index()
    {
        $user = Users::find(session('user_id'));

        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Acesso não autorizado.');
        }

        // Buscar todas as entregas feitas em atividades da turma do admin
        $entregas = Entrega::with(['user', 'atividade'])
            ->whereNull('nota')
            ->whereHas('atividade', function ($query) use ($user) {
                $query->where('turma_id', $user->turma_id);
            })
            ->get();


        return view('entregas.index', compact('entregas'));
    }

    // Admin atribui nota para uma entrega
    public function atribuirNota(Request $request, $id)
    {
        $request->validate([
            'nota' => 'required|numeric|min:0|max:10',
        ]);

        $entrega = Entrega::findOrFail($id);
        $entrega->nota = $request->nota;
        $entrega->save();

        return redirect()->back()->with('success', 'Nota atribuída com sucesso.');
    }
    public function corrigidas()
{
    $user = Users::find(session('user_id'));

    if (!$user || $user->role !== 'admin') {
        return redirect()->route('login')->with('error', 'Acesso não autorizado.');
    }

    $entregas = Entrega::with(['user', 'atividade'])
        ->whereNotNull('nota')
        ->whereHas('atividade', function ($query) use ($user) {
            $query->where('turma_id', $user->turma_id);
        })
        ->get();

    return view('entregas.corrigidas', compact('entregas'));
}

}
