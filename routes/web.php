<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContratoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocusignController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ValidacaoController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\TransparenciaController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\DashboardController;
use App\Models\Contrato;

use App\Http\Controllers\AtividadeController;
// Página inicial (redireciona para o formulário de upload)
Route::get('/', function () {
    return redirect()->route('login.view');
});


Route::get('login', [AuthController::class, 'showLogin'])->name('login.view');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('login.logout');



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

Route::get('/teste-contrato', function () {
    return view('contratos.index');
});
Route::get('/contrato', [ContratoController::class, 'showContract'])->name('contratos.view');


// Rota para processar o envio do contrato
Route::post('/contratos/upload', [ContratoController::class, 'uploadContract'])->name('contratos.upload.process');
Route::get('/contratos/download/{filename}', [ContratoController::class, 'download'])->name('contrato.download');
Route::delete('/contratos/delete/{id}', [ContratoController::class, 'delete'])->name('contrato.delete');



Route::get('/files', [FileController::class, 'show'])->name('files.view');
Route::post('/files/upload', [FileController::class, 'upload'])->name('files.upload');
Route::post('/files/confirm/{id}', [FileController::class, 'confirm'])->name('files.confirm');
Route::post('/files/reject/{id}', [FileController::class, 'reject'])->name('files.reject');
Route::get('/files/download/{filename}', [FileController::class, 'download'])->name('files.download');
Route::delete('/files/delete/{id}', [FileController::class, 'delete'])->name('files.delete');

Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedbacks.index');
Route::get('/feedbacks/create', [FeedbackController::class, 'create'])->name('feedbacks.create');
Route::post('/feedbacks', [FeedbackController::class, 'store'])->name('feedbacks.store');


Route::get('/transparencia', [TransparenciaController::class, 'showList'])->name('transparencia.lista');



Route::get('/atividades', [AtividadeController::class, 'index'])->name('atividades.index');
Route::get('/atividades/criar', [AtividadeController::class, 'create'])->name('atividades.create');
Route::post('/atividades', [AtividadeController::class, 'store'])->name('atividades.store');


Route::post('/entregas', [EntregaController::class, 'store'])->name('entregas.store');
Route::get('/entregas', [EntregaController::class, 'index'])->name('entregas.index');
Route::post('/entregas/{id}/nota', [EntregaController::class, 'atribuirNota'])->name('entregas.nota');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.view');