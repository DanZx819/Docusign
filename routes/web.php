<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocusignController;

// Página inicial (redireciona para o formulário de upload)
Route::get('/', function () {
    return redirect()->route('docusign.upload');
});

// Página para o upload de arquivos PDF
Route::get('docusign', [DocusignController::class, 'showForm'])->name('docusign.upload');

// Processamento de upload de arquivos PDF
Route::post('docusign/upload', [DocusignController::class, 'uploadPdf'])->name('docusign.upload.pdf');

// Autenticação com DocuSign
Route::get('connect-docusign', [DocusignController::class, 'connectDocusign'])->name('connect.docusign');
Route::get('docusign/callback', [DocusignController::class, 'callback'])->name('docusign.callback');

// Assinatura de documento (passando o nome do arquivo como parâmetro)
Route::get('sign-document/{filename}', [DocusignController::class, 'signDocument'])->name('docusign.sign');

Route::get('docusign/return', [DocusignController::class, 'return'])->name('docusign.return');

