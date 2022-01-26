@extends('layouts.app')
@section('content')

<!-- https://codepen.io/wizly/pen/BlKxo -->
<div class="container">	
  <h1 class="display-one text-center">Forum</h1>
  <div class="border p-2 rounded-2 border-dark">
    <ul class="nav nav-tabs">
      <li class="active">
        <a  href="#1" data-toggle="tab">@lang('lang.english')</a>
      </li>
      <li><a href="#2" data-toggle="tab">@lang('lang.french')</a>
      </li>
    </ul>
      <form method="POST" action="{{ route('create.article') }}">
            @csrf
          <div class="tab-content">
            <div class="tab-pane active" id="1">
              <div class="d-flex flex-column">
                <input type="text" name="titre_en" class="form-control mt-2 mb-2" placeholder="@lang('lang.title')" required>
                  <textarea rows="5" name="contenu_en" placeholder=" @lang('lang.write_msg')" maxlength="255" required></textarea>
              </div>
            </div>
            <div class="tab-pane" id="2">
              <div class="d-flex flex-column">
                <input type="text" name="titre_fr" class="form-control mt-2 mb-2" placeholder="@lang('lang.title')" required>
                  <textarea rows="5" name="contenu_fr" placeholder=" @lang('lang.write_msg')" maxlength="255" required></textarea>
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
  @php $locale = session()->get('locale'); @endphp
  @forelse($articles as $article)
      <div class="border p-3 m-3 shadow-sm">
        @if(session()->get('user')->id == $article->etudiant_id || session()->get('user')->is_admin == 1)
          <div class="d-flex">
            <a class="me-3 ms-auto" href="{{ route('edit.article', $article->id) }}">@lang('lang.edit')</a>
            <form  action="{{ route('delete.article', $article->id) }}" method="POST">
              @method('DELETE')
              @csrf
              <button class="supprime-btn">@lang('lang.delete')</button>
            </form>
          </div>
        @endif
        <h4 class="display-four text-center mb-2">{{$article->titre}}</h4>
        <p>{{$article->contenu}}</p>
        <div class="d-flex">
        
          <span class="fw-bold me-3 ms-auto"> @if(!isset($article->etudiant->nom))[@lang('lang.deleted')] @else {{$article->etudiant->nom}} @endif</span>
          <span>{{$article->updated_at}}</span>
        </div>
      </div>    
  @empty
      <div class="text-warning">@lang('lang.no_message')</div>
  @endforelse
  </div>
@endsection
