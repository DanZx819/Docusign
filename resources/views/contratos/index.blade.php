@extends('templates.layout')

@section('content')
<div class="container">


    @if ($user && $user->role === 'admin')
    <div class="container">
    <h2>Upload de Contratos</h2>
    <form action="{{ route('contratos.upload.process') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="title">Título</label>
        <input type="text" name="title" maxlength="64">
        <label for="descricao">Descrição</label>
        <input type="text" name="description" maxlength="256">

        <input type="file" name="file" accept="application/pdf">
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>

@endif



    <h2>Meus Contratos</h2>

    <div class="row mt-4">
        @forelse ($files as $file)
            <div class="col-md-6">
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $file->title }}</h5>
                        <p class="card-text">{{ $file->description }}</p>

                        <div class="d-flex gap-2">
                            <a href="{{ route('docusign.sign', ['filename' => $file->filename]) }}" class="btn btn-success">
                                Assinar Contrato
                            </a>

                            <a href="{{ route('contrato.download', ['filename' => $file->filename]) }}" class="btn btn-secondary">
                                Baixar PDF
                            </a>
                             @if ($user && $user->role === 'admin')
                                <!-- Botão de excluir (visível apenas para admins) -->
                                <form action="{{ route('contrato.delete', ['id' => $file->id]) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este contrato?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Excluir</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>Nenhum contrato disponível no momento.</p>
        @endforelse
    </div>
</div>
@endsection
