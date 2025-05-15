<?php

namespace App\Http\Controllers;
use App\Models\Card;
use Illuminate\Http\Request;
use App\Models\Contrato;
class ContratoController extends Controller
{
    public function showContract()
{
    $user = \App\Models\Users::find(session('user_id'));

    if (!$user) {
        return redirect()->route('login')->with('error', 'Usuário não autenticado');
    }

    $files = Contrato::all();

    return view('contratos.index', compact('files', 'user'));
}




public function uploadContract(Request $request)
{
    // Validação do arquivo
    $request->validate([
        'file' => 'required|mimes:pdf|max:10240', // Limite de 10MB para PDF
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:500',
    ]);

    // Salvando o arquivo na pasta public/contratos
    $uploadPath = public_path('contratos');
    
    // Verificando se a pasta existe, senão, criando-a
    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0775, true);
    }

    // Gerando o nome do arquivo
    $filename = $request->file('file')->getClientOriginalName();

    // Movendo o arquivo para o diretório de uploads
    $fileMoved = $request->file('file')->move($uploadPath, $filename);

    if (!$fileMoved) {
        return redirect()->back()->with('error', 'Erro ao salvar o arquivo.');
    }

    // Criando o contrato no banco de dados
    $contract = Contrato::create([
        'title' => $request->title,
        'description' => $request->description,
        'filename' => $filename,  // Salvando o nome do arquivo
    ]);

    if (!$contract) {
        // Caso falhe a inserção no banco de dados
        return redirect()->back()->with('error', 'Erro ao salvar o contrato no banco de dados.');
    }

    // Redirecionando de volta com uma mensagem de sucesso
    return redirect()->route('contratos.view')->with('success', 'Contrato enviado com sucesso!');
}

public function download($filename)
{
    $filePath = public_path('contratos/' . $filename);
    if (file_exists($filePath)) {
        return response()->download($filePath);
    } else {
        return redirect()->back()->with('error', 'Arquivo não encontrado.');
    }
}

public function delete($id)
{
    // Procurando o contrato no banco de dados
    $file = Contrato::find($id);

    // Verificando se o arquivo existe
    if ($file) {
        // Remover o arquivo físico do diretório
        $filePath = public_path('contratos/' . $file->filename);
        if (file_exists($filePath)) {
            unlink($filePath); // Exclui o arquivo do diretório
        }

        // Remover o registro do banco de dados
        $file->delete();

        // Redirecionar de volta com uma mensagem de sucesso
        return redirect()->route('contratos.view')->with('success', 'Contrato excluído com sucesso!');
    } else {
        // Caso o contrato não exista
        return redirect()->route('contratos.view')->with('error', 'Contrato não encontrado.');
    }
}



    
}