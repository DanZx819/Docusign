<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
class FileController extends Controller
{
    public function show()
    {
        $files = File::all();
        return view('validação.validacao', compact('files'));
    }
    public function upload(Request $request)
{
    $request->validate([
        'title' => 'required',
        'pdfenv' => 'required|mimes:pdf|max:2048'
    ]);

    $file = $request->file('pdfenv');
    $filename = time() . '_' . $file->getClientOriginalName();

    // Caminho absoluto para public/sign
    $destinationPath = public_path('sign');

    // Cria a pasta se não existir
    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }

    // Move o arquivo para public/sign
    $file->move($destinationPath, $filename);

    // Salva no banco
    File::create([
        'title' => $request->title,
        'description' => $request->description,
        'filename' => $filename,
        'status' => 0
    ]);

    return redirect()->route('docusign.upload')->with('success', 'Arquivo enviado!');
}


    public function confirm($id)
    {
        File::where('id', $id)->update(['status' => 2]);
        return back()->with('success', 'Arquivo confirmado!');
    }

    public function reject($id)
    {
        File::where('id', $id)->update(['status' => 1]);
        return back()->with('success', 'Arquivo rejeitado!');
    }

    public function download($filename)
{
    $path = public_path("sign/{$filename}");

    if (!file_exists($path)) {
        abort(404, 'Arquivo não encontrado.');
    }

    return response()->download($path);
}
public function delete($id)
{
    // Encontrar o arquivo no banco de dados
    $file = File::findOrFail($id);

    // Caminho do arquivo
    $filePath = public_path("sign/{$file->filename}");

    // Verificar se o arquivo existe e excluir
    if (file_exists($filePath)) {
        unlink($filePath);  // Excluir o arquivo da pasta
    }

    // Remover a entrada do banco de dados
    $file->delete();

    return back()->with('success', 'Arquivo excluído com sucesso!');
}


}
