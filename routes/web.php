<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocusignController;
use App\Http\Controllers\ValidacaoController;
use App\Http\Controllers\FileController;
// Página inicial (redireciona para o formulário de upload)
Route::get('/', function () {
    return redirect()->route('login.view');
});


Route::get('login', [AuthController::class, 'showLogin'])->name('login.view');
Route::post('/login', [AuthController::class, 'login']);

// Página para o upload de arquivos PDF
Route::get('docusign', [DocusignController::class, 'showForm'])->name('docusign.upload');

// Processamento de upload de arquivos PDF
Route::post('docusign/upload', [DocusignController::class, 'uploadPdf'])->name('docusign.upload.pdf');

// Início do processo de autenticação com DocuSign
Route::get('connect-docusign', [DocusignController::class, 'authenticate'])->name('docusign.authenticate');

// Callback da autenticação (recebe o código de autorização do DocuSign)
Route::get('docusign/callback', [DocusignController::class, 'callback'])->name('docusign.callback');

// Assinatura de documento (passando o nome do arquivo como parâmetro)
Route::get('docusign/sign/{filename}', [DocusignController::class, 'signDocument'])->name('docusign.sign'); // Verifica se o usuário está autenticado no DocuSign

// Callback após a assinatura do documento
Route::get('docusign/return', [DocusignController::class, 'return'])->name('docusign.return');

Route::delete('/docusign/delete/{id}', [DocusignController::class, 'delete'])->name('docusign.delete');



Route::get('/files', [FileController::class, 'show'])->name('files.view');
Route::post('/files/upload', [FileController::class, 'upload'])->name('files.upload');
Route::post('/files/confirm/{id}', [FileController::class, 'confirm'])->name('files.confirm');
Route::post('/files/reject/{id}', [FileController::class, 'reject'])->name('files.reject');
Route::get('/files/download/{filename}', [FileController::class, 'download'])->name('files.download');
Route::delete('/files/delete/{id}', [FileController::class, 'delete'])->name('files.delete');

