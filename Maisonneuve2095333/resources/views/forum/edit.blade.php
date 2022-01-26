@extends('layouts.app')
@section('content')

<div class="container">	
  <h1 class="display-one text-center">@lang('lang.modify_msg')</h1>
  <div class="border p-2 rounded-2 border-dark">
    <ul class="nav nav-tabs">
      <li class="active">
        <a  href="#1" data-toggle="tab">@lang('lang.english')</a>
      </li>
      <li><a href="#2" data-toggle="tab">@lang('lang.french')</a>
      </li>
    </ul>
      <form method="POST" action=" {{route('update.article' , $article->id)}}">
            @csrf
            @method('PUT')
          <div class="tab-content">
            <div class="tab-pane active" id="1">
              <div class="d-flex flex-column">
                <input type="text" name="titre_en" class="form-control mt-2 mb-2" placeholder="@lang('lang.title')" value="{{$article->titre_en}}" required>
                  <textarea rows="5" name="contenu_en" placeholder="@lang('lang.write_msg')" maxlength="255" required>{{$article->contenu_en}}</textarea>
              </div>
            </div>
            <div class="tab-pane" id="2">
              <div class="d-flex flex-column">
                <input type="text" name="titre_fr" class="form-control mt-2 mb-2" placeholder="@lang('lang.title')" value="{{$article->titre_fr}}" required>
                  <textarea rows="5" name="contenu_fr" placeholder="@lang('lang.write_msg')" maxlength="255" required>{{$article->contenu_fr}}</textarea>
              </div>
            </div>
          </div>
          <div class="d-grid ms-auto">
            <button type="submit" class="btn btn-primary ms-auto mt-2">@lang('lang.save')</button>
        </div>
        @if ($errors->has('titre_fr'))
          <span class="text-danger">{{ $errors->first('titre_fr') }}</span>
        @endif
        @if ($errors->has('contenu_en'))
          <span class="text-danger">{{ $errors->first('contenu_en') }}</span>
        @endif
        @if ($errors->has('contenu_fr'))
          <span class="text-danger">{{ $errors->first('contenu_fr') }}</span>
        @endif
      </form>
  </div>
  </div>
@endsection
