@extends('layouts.app')
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12 pt-2">
            @if(session()->has('user') and session('user')->is_admin == 1)
            <div class="col-4 ms-2 mt-2"><a href="{{ route('etudiant.create')}}" class="btn btn-primary btn-sm">@lang('lang.add_student')</a></div>
            @endif
                <div class="row">

                        <h1 class="display-one text-center">@lang('lang.student_list')</h1>
                        </div>
                     
                
                <ul class="row mt-4">
                @forelse($etudiants as $etudiant)
                    <li class="col-4"><a href="{{ route('etudiant.show', $etudiant->id) }}">{{ ucfirst($etudiant->nom) }}</a></li>    
                @empty
                    <p class="text-warning">@lang('lang.student')</p>
                @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection