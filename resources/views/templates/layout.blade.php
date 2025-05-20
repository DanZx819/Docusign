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
    <div class="container d-flex justify-content-between align-items-center">
        <!-- Logo à esquerda -->
        <a class="navbar-brand" href="{{ route('docusign.upload') }}">DocuSign Laravel</a>

        <!-- Grupo à direita -->
        <div class="d-flex align-items-center ms-auto">
            <!-- Link Transparência à esquerda do dropdown -->
            <a class="navbar-brand me-3" href="{{ route('transparencia.lista') }}">Transparência</a>

            <!-- Dropdown -->
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ $user->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    @if ($user && $user->role === 'admin')
                        <li><a class="dropdown-item" href="{{ route('files.view') }}">Menu Admin</a></li>
                    @endif
                    <li><a class="dropdown-item" href="{{ route('atividades.index') }}">Atividades</a></li>
                    @if ($user && $user->role === 'admin')
                        <li><a class="dropdown-item" href="{{ route('atividades.create') }}">Criar Atividade</a></li>
                    @endif
                    <li><a href="{{ route('docusign.upload') }}" class="dropdown-item">Documentos</a></li>
                    
                    <li><a class="dropdown-item" href="{{ route('contratos.view') }}">Contratos</a></li>
                    <li><a class="dropdown-item" href="{{ route('feedbacks.index') }}">FeedBack</a></li>
                    <li><a class="dropdown-item" href="{{ route('login.logout') }}">Sair</a></li>
                </ul>
            </div>
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
