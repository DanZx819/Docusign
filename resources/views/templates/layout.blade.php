<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DocuSign App')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="template.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('docusign.upload') }}">DocuSign Laravel</a>

            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  {{$user->name}}
                </button>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('docusign.upload') }}" class="dropdown-item">Documentos</a></li>
                  <li>@if ($user && $user->role === 'admin')
                    <a class="dropdown-item" href="{{ route('files.view') }}">Menu Admin</a>
                @endif</li>
                  <li><a class="dropdown-item" href="{{ route('contratos.view') }}">Contratos</a></li>
                  <li><a class="dropdown-item" href="#">Sair</a></li>
                </ul>
              </div>
            
        </div>
    </nav>

    <main class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
        <a href="https://web.whatsapp.com/send?phone=18996830098" class="btn btn-success bg-opacity-50 position-fixed end-0 bottom-0 me-5 mb-5 rounded-circle" ><i class="bi bi-whatsapp"></i></a>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
