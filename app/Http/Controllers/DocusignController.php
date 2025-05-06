<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DocuSign\eSign\Configuration;
use DocuSign\eSign\Api\EnvelopesApi;
use DocuSign\eSign\Client\ApiClient;
use DocuSign\eSign\Model\Document;
use DocuSign\eSign\Model\EnvelopeDefinition;
use DocuSign\eSign\Model\Recipients;
use DocuSign\eSign\Model\Signer;
use DocuSign\eSign\Model\SignHere;
use DocuSign\eSign\Model\Tabs;
use DocuSign\eSign\Model\RecipientViewRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Log;

use Exception;

class DocusignController extends Controller
{
    private $signer_client_id = 1000;

    // Página de upload de PDFs
    public function showForm()
    {
        $uploadPath = public_path('uploads');
    
        $files = file_exists($uploadPath)
            ? collect(scandir($uploadPath))->filter(function ($file) use ($uploadPath) {
                return is_file($uploadPath . DIRECTORY_SEPARATOR . $file) && Str::endsWith($file, '.pdf');
            })->values()
            : collect([]);
    
        return view('docusign.upload', compact('files'));
    }
    
    

    // Função para processar o upload dos PDFs
    public function uploadPdf(Request $request)
    {
        // Validação dos arquivos enviados
        $request->validate([
            'pdfs.*' => 'required|mimes:pdf|max:10240',
        ]);
    
        // Verifique o caminho do diretório de upload
        $uploadPath = public_path('uploads');
    
        // Verifique se o diretório de uploads existe, e crie-o se necessário
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0775, true); // Cria a pasta, se necessário
        }
    
        // Verifique se o diretório é gravável (somente para depuração no Windows)
        if (!is_writable($uploadPath)) {
            return redirect()->back()->with('error', 'O diretório de upload não tem permissões de escrita.');
        }
    
        // Processa cada arquivo PDF
        foreach ($request->file('pdfs') as $file) {
            // Nome do arquivo
            $fileName = $file->getClientOriginalName();
    
            // Mover o arquivo para o diretório 'uploads'
            $file->move($uploadPath, $fileName); // Método adequado no Windows
    
            // Log para verificar se o arquivo foi movido
            Log::info("Arquivo {$fileName} enviado para o diretório {$uploadPath}");
        }
    
        return redirect()->back()->with('success', 'PDFs enviados com sucesso.');
    }
    



    // Assinar o documento PDF
    public function signDocument($filename)
    {
        try {
            $path = $path = public_path('uploads/' . $filename);
            

            if (!file_exists($path)) {
                return redirect()->back()->with('error', 'Arquivo não encontrado.');
            }

            $access_token = Session::get('docusign_auth_code');
            $account_id = env('DOCUSIGN_ACCOUNT_ID');
            $base_path = env('DOCUSIGN_BASE_URL');

            $config = new Configuration();
            $config->setHost($base_path);
            $config->addDefaultHeader('Authorization', 'Bearer ' . $access_token);
            $api_client = new ApiClient($config);
            $envelope_api = new EnvelopesApi($api_client);

            // Ler o arquivo PDF e convertê-lo para base64
            $content_bytes = file_get_contents($path);
            $base64_file_content = base64_encode($content_bytes);

            $document = new Document([
                'document_base64' => $base64_file_content,
                'name' => $filename,
                'file_extension' => 'pdf',
                'document_id' => 1,
            ]);

            // Criar o signatário
            $signer = new Signer([
                'email' => 'shaivroy1@gmail.com',
                'name' => 'Shaiv',
                'recipient_id' => "1",
                'routing_order' => "1",
                'client_user_id' => $this->signer_client_id,
            ]);

            // Adicionar o campo de assinatura no documento
            $sign_here = new SignHere([
                'anchor_string' => '/sn1/',  // Posição do campo de assinatura
                'anchor_units' => 'pixels',
                'anchor_y_offset' => '10',
                'anchor_x_offset' => '20',
            ]);

            $signer->setTabs(new Tabs(['sign_here_tabs' => [$sign_here]]));

            // Definir envelope com o documento e o signatário
            $envelope_definition = new EnvelopeDefinition([
                'email_subject' => "Por favor, assine este documento: $filename",
                'documents' => [$document],
                'recipients' => new Recipients(['signers' => [$signer]]),
                'status' => "sent",  // Enviar imediatamente para assinatura
            ]);

            // Criar o envelope no DocuSign
            $envelope = $envelope_api->createEnvelope($account_id, $envelope_definition);
            $envelope_id = $envelope->getEnvelopeId();

            // Criar a visualização do receptor para assinatura
            $recipient_view_request = new RecipientViewRequest([
                'authentication_method' => 'none',
                'client_user_id' => $this->signer_client_id,
                'recipient_id' => '1',
                'return_url' => route('docusign.return'),
                'user_name' => 'Shaiv',
                'email' => 'shaivroy1@gmail.com',
            ]);

            // Gerar a URL de assinatura
            $results = $envelope_api->createRecipientView($account_id, $envelope_id, $recipient_view_request);

            return redirect()->to($results->getUrl());  // Redirecionar o usuário para a URL de assinatura

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao iniciar assinatura: ' . $e->getMessage());
        }
    }

    // Callback após o usuário assinar o documento
    public function return()
    {
        return redirect()->route('docusign.upload')->with('success', 'Documento assinado com sucesso!');
    }
}
