@extends('templates.layout')


@section('content')

    

{{-- Exibir o formulário apenas para admins --}}
@if ($user && $user->role === 'admin')
    <div class="container">
    <h2>Upload de PDFs</h2>
    <form action="{{ route('docusign.upload.pdf') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="title">Titulo</label>
        <input type="text" name="title" maxlength="64">
        <label for="descricao">Descrição</label>
        <input type="text" name="description" maxlength="256">

        <input type="file" name="pdfs[]" accept="application/pdf">
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
@endif

    
    
    <hr>

    <h3>Documentos enviados</h3>

    <div class="d-flex flex-wrap">
        @forelse ($files as $file)
            <div class="col-md-4">
                <div class="card mb-3 d-flex justify-content-start mt-3">
                    <div class="card-body">
                        <h5 class="card-title">{{$file->title}}</h5>
                        <h5 class="card-text">{{$file->description}}</h5>
                        <a href="{{ route('docusign.sign', ['filename' => $file->filename]) }}" class="btn btn-success">Assinar</a>
                        @if ($user && $user->role === 'admin')
                            <form action="{{ route('docusign.delete', $file->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este arquivo?')" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Excluir</button>
                            </form>
                        @endif
                        <div  class="d-flex justify-content-end mt-3">
                            <form action="{{route('files.upload')}}" method="post" class="d-flex gap-2" enctype="multipart/form-data">
                                @csrf
                                <form action="{{route('files.upload')}}" method="post" class="d-flex gap-2" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="title" value="{{ $file->title }}">
                                    <input type="hidden" name="description" value="{{ $file->description }}">
                                    
                                    <input type="file" name="pdfenv" accept="application/pdf" class="form-control w-70" required>
                                    <button type="submit" class="btn btn-success">Enviar</button>
                                </form>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>Nenhum arquivo enviado ainda.</p>
        @endforelse
    </div>
</div>
@endsection

