@extends('layouts.app')

@section('content')
<!-- https://www.positronx.io/laravel-file-upload-with-validation/ -->
@if ($message = Session::get('success'))
            <div class="alert alert-success">
                <strong>{{ $message }}</strong>
            </div>
          @endif

          @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          @endif
    <div class="container mt-5">
    <h3 class="text-center mb-5">@lang('lang.repository')</h3>
        <div class="border border-dark rounded-3 p-3 m-3">
            <form action="{{route('fileUpload')}}" method="post" enctype="multipart/form-data"> 
                @csrf
                <div>
                <input type="text" name="titre_en" class="form-control mt-2 mb-2" placeholder="@lang('lang.upload_en')" value="{{old('titre_en')}}" required>
                </div>
                <div>
                <input type="text" name="titre_fr" class="form-control mt-2 mb-2" placeholder="@lang('lang.upload_fr')" value="{{old('titre_fr')}}" required>
                </div>
                <div class="mb-2">
                    <input class="form-control" type="file" id="file" name="file" required>
                </div>
                <div class="d-grid ms-auto">
                    <button type="submit" name="submit" class="btn btn-primary btn-block mt-3 ms-auto">
                    @lang('lang.upload')
                    </button>
                </div>
            </form>
        </div>

        <h4 class="text-center mt-5 mb-5">@lang('lang.document_list')</h4>
        
        @php $locale = session()->get('locale'); @endphp
        <table class="table table-striped mt-5">
                        <thead>
                            <tr>
                                <th scope="col">Document</th>
                                <th scope="col">@lang('lang.by')</th>
                                <th scope="col">Date</th>
                                <th scope="col">@lang('lang.download')</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($documents as $document)
                            <tr>
                                <td>{{$document->titre}}</td>
                                <td> @if(!isset($document->etudiant->nom))[@lang('lang.deleted')] @else {{$document->etudiant->nom}} @endif</td>
                                <td>{{$document->updated_at}}</td>
                                <td><a href="{{ url('download?path='.$document->file) }}">@lang('lang.download')</a>
                                @if(session()->get('user')->id == $document->etudiant_id || session()->get('user')->is_admin == 1)
                                
                                <a class="me-3 ms-3" href="{{ route('edit.document', $document->id) }}">@lang('lang.edit')</a>
                                <form class="d-inline"  action="{{ route('delete.document', $document->id) }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button class="supprime-btn">@lang('lang.delete')</button>
                                </form>
                                </td>
                            @endif
                        </div>
                    @empty
                        <td class="fw-bold">@lang('lang.no_document')</td>
                    @endforelse
                        </tbody>
                    </table>
    </div>
    @endsection