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
    <h3 class="text-center mb-5">@lang('lang.edit_document')</h3>
        <div class="border border-dark rounded-3 p-3 m-3">
            <form action="{{route('update.document', $document->id)}}" method="post" enctype="multipart/form-data"> 
                @csrf
                @method('PUT')
                <div>
                <input type="text" name="titre_en" class="form-control mt-2 mb-2" placeholder="@lang('lang.upload_en')" value="{{$document->titre_en}}" required>
                </div>
                <div>
                <input type="text" name="titre_fr" class="form-control mt-2 mb-2" placeholder="@lang('lang.upload_fr')" value="{{$document->titre_fr}}" required>
                </div>
                <div class="mb-2">
                    <label for="formFileMultiple" class="form-label">@lang('lang.filename') : <b>{{explode('/',$document->file)[1]}}</b></label>
                    <input class="form-control" type="file" id="formFileMultiple" name="file">
                </div>
                <div class="d-grid ms-auto">
                    <button type="submit" name="submit" class="btn btn-primary btn-block mt-3 ms-auto">
                    @lang('lang.upload')
                    </button>
                </div>
            </form>
        </div>

     
    </div>
  
    @endsection