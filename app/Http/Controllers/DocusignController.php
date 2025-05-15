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
use App\Models\Card;
use Exception;
use Illuminate\Support\Facades\Http;

class DocusignController extends Controller
{
    private $signer_client_id = 1000; // Identificador do signatário interno (client_user_id)

    // Página de upload de PDFs (mantido como solicitado)
    public function showForm()
    {
        // Pega o usuário da sessão
        $user = \App\Models\Users::find(session('user_id'));
    
        // Verifica se o usuário existe
        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuário não autenticado');
        }
    
        // Pega todos os cards filtrados pela turma do usuário
        $files = Card::where('turma_id', $user->turma_id)->latest()->get();
    
        // Retorna a view com os dados dos cards e o usuário
        return view('docusign.upload', compact('files', 'user'));
    }
   



    public function uploadPdf(Request $request)
    {
        // Validação dos campos
        $request->validate([
            'title' => 'required|string|max:64',
            'description' => 'nullable|string|max:256',
            'pdfs.*' => 'required|mimes:pdf|max:10240', // Máximo de 10MB por arquivo
        ]);
    
        // Caminho para salvar os arquivos PDF
        $uploadPath = public_path('uploads');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }
    
        // Obter o usuário da sessão
        $user = \App\Models\Users::find(session('user_id'));
    
        // Verificar se o usuário foi encontrado
        if (!$user) {
            return back()->withErrors(['error' => 'Usuário não encontrado.']);
        }
    
        // Debug para verificar as informações do usuário
        
    
        foreach ($request->file('pdfs') as $file) {
            // Gerar nome único para o arquivo
            $fileName = uniqid() . '_' . $file->getClientOriginalName();
    
            // Mover o arquivo para o diretório de uploads
            $file->move($uploadPath, $fileName);
    
            // Debug para verificar o nome do arquivo e o turma_id
            
    
            // Salvar as informações no banco de dados
            Card::create([
                'title' => $request->title,
                'description' => $request->description, // ou $request->descricao
                'filename' => $fileName,
                'turma_id' => $user->turma_id,
            ]);
        }
    
        return redirect()->back()->with('success', 'PDFs enviados com sucesso.');
    }
    
    
    
    
    

    // Iniciar autenticação OAuth (DocuSign)
    public function authenticate()
    {
        $query = http_build_query([
            'response_type' => 'code',
            'scope' => 'signature',
            'client_id' => env('DOCUSIGN_CLIENT_ID'),
            'redirect_uri' => env('DOCUSIGN_REDIRECT_URI'),
        ]);

        return redirect(env('DOCUSIGN_AUTH_URL') . '?' . $query);
    }

    // Callback do DocuSign (troca código por token de acesso)
    public function callback(Request $request)
    {
        $code = $request->get('code');
    
        $client_id = env('DOCUSIGN_CLIENT_ID');
        $client_secret = env('DOCUSIGN_CLIENT_SECRET');
        $redirect_uri = route('docusign.callback');
        $base_url = 'https://account-d.docusign.com';
    
        // Trocar o código de autorização por token de acesso
        $response = Http::asForm()->post("$base_url/oauth/token", [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'redirect_uri' => $redirect_uri,
        ]);
    
        if ($response->failed()) {
            return redirect()->route('docusign.upload')->with('error', 'Erro ao obter token de acesso.');
        }
    
        $data = $response->json();
        $access_token = $data['access_token'];
    
        // Buscar informações do usuário (inclui o account_id)
        $userInfo = Http::withToken($access_token)->get("$base_url/oauth/userinfo");
    
        if ($userInfo->failed()) {
            return redirect()->route('docusign.upload')->with('error', 'Erro ao obter informações do usuário.');
        }
    
        $info = $userInfo->json();
        $account_id = $info['accounts'][0]['account_id'];
    
        // Armazenar na sessão
        Session::put('docusign_access_token', $access_token);
        Session::put('docusign_account_id', $account_id);
    
        return redirect()->route('docusign.upload')->with('success', 'Autenticado com sucesso.');
    }
    

    // Assinar documento
    public function signDocument($filename)
    {
        try {
            $path = public_path('uploads/' . $filename);
            if (!file_exists($path)) {
                return redirect()->back()->with('error', 'Arquivo não encontrado.');
            }

            $access_token = Session::get('docusign_access_token');

            if (!$access_token) {
                return redirect()->route('docusign.authenticate');
            }

            $account_id = Session::get('docusign_account_id');

            $base_path = env('DOCUSIGN_BASE_URL');

            $config = new Configuration();
            $config->setHost($base_path);
            $config->addDefaultHeader('Authorization', 'Bearer ' . $access_token);

            $api_client = new ApiClient($config);
            $envelope_api = new EnvelopesApi($api_client);

            $content_bytes = file_get_contents($path);
            $document = new Document([
                'document_base64' => base64_encode($content_bytes),
                'name' => $filename,
                'file_extension' => 'pdf',
                'document_id' => 1,
            ]);

            $signer = new Signer([
                'email' => 'shaivroy1@gmail.com', // Substitua futuramente pelo e-mail dinâmico
                'name' => 'Shaiv',
                'recipient_id' => "1",
                'routing_order' => "1",
                'client_user_id' => $this->signer_client_id,
            ]);

            $sign_here = new SignHere([
                'anchor_string' => '/sn1/',
                'anchor_units' => 'pixels',
                'anchor_y_offset' => '10',
                'anchor_x_offset' => '20',
            ]);

            $signer->setTabs(new Tabs(['sign_here_tabs' => [$sign_here]]));

            $email_subject = "Por favor, assine este documento: " . substr($filename, 0, 80); // Trunca o nome do arquivo para garantir que o limite não seja excedido
            $email_subject = strlen($email_subject) > 100 ? substr($email_subject, 0, 100) : $email_subject;
            
            $envelope_definition = new EnvelopeDefinition([
                'email_subject' => $email_subject,
                'documents' => [$document],
                'recipients' => new Recipients(['signers' => [$signer]]),
                'status' => "sent",
            ]);
            

            $envelope = $envelope_api->createEnvelope($account_id, $envelope_definition);
            $envelope_id = $envelope->getEnvelopeId();

            $view_request = new RecipientViewRequest([
                'authentication_method' => 'none',
                'client_user_id' => $this->signer_client_id,
                'recipient_id' => '1',
                'return_url' => route('docusign.return'),
                'user_name' => 'Shaiv',
                'email' => 'shaivroy1@gmail.com',
            ]);

            $results = $envelope_api->createRecipientView($account_id, $envelope_id, $view_request);

            return redirect()->to($results->getUrl());

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao iniciar assinatura: ' . $e->getMessage());
        }
    }

    // Callback após assinatura
    public function return()
    {
        return redirect()->route('docusign.upload')->with('success', 'Documento assinado com sucesso!');
    }
    
    public function delete($id)
{
    $card = Card::findOrFail($id);

    // Caminho do arquivo físico
    $filePath = public_path('uploads/' . $card->filename);

    // Deletar o arquivo se existir
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // Deletar o registro no banco
    $card->delete();

    return redirect()->back()->with('success', 'Arquivo excluído com sucesso!');
}

}
