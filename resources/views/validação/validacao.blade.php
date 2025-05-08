@extends('templates.layout')



@section('content')


    <h3>Arquivos enviados</h3>
    <div class="row">
        @forelse ($files as $file)
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5>{{ $file->title }}</h5>
                        <p>{{ $file->description }}</p>
                        <p>Status: 
                            @if($file->status == 0) <span class="text-warning">Em Processamento</span>
                            @elseif($file->status == 1) <span class="text-danger">Rejeitado</span>
                            @else <span class="text-success">Confirmado</span>
                            @endif
                        </p>

                        <a href="{{ route('files.download', $file->filename) }}" class="btn btn-primary mb-2">Baixar PDF</a>

                        <form action="{{ route('files.confirm', $file->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-success mb-2">Confirmar</button>
                        </form>

                        <form action="{{ route('files.reject', $file->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-warning mb-2">Rejeitar</button>
                        </form>
                        <form action="{{ route('files.delete', $file->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')  <!-- Usando DELETE porque é um tipo de requisição HTTP -->
                            <button class="btn btn-danger mb-2" onclick="return confirm('Tem certeza que deseja excluir este arquivo?')">Excluir</button>
                        </form>
                        
                    </div>
                </div>
            </div>
        @empty
            <p>Nenhum arquivo enviado.</p>
        @endforelse
    </div>
</div>
@endsection
