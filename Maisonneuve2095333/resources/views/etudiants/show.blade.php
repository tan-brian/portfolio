@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 pt-2">
                <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-sm">@lang('lang.return')</a>
                <h1 class="display-one text-center">{{ ucfirst($etudiant->nom) }}</h1>
                <hr>
                <div class="row border border-bottom-0">
                    <span class="col-4 border-end fw-bold">@lang('lang.name') :</span>
                    <span class="col-4">{{ ucfirst($etudiant->nom) }}</span>
                </div>
                <div class="row border border-bottom-0">
                    <span class="col-4 border-end fw-bold">@lang('lang.city') :</span>
                    <span class="col-4">{{$etudiant->ville ? ucfirst($etudiant->ville->nom) : '' }}</span>
                </div>
                <div class="row border border-bottom-0">
                    <span class="col-4 border-end fw-bold">@lang('lang.address') :</span>
                    <span class="col-4">{{ $etudiant->adresse }}</span>
                </div>
                <div class="row border border-bottom-0">
                    <span class="col-4 border-end fw-bold">@lang('lang.phone') :</span>
                    <span class="col-4">{{ $etudiant->phone }}</span>
                </div>
                <div class="row border border-bottom-0">
                    <span class="col-4 border-end fw-bold">@lang('lang.email') :</span>
                    <span class="col-4">{{ $etudiant->email }}</span>
                </div>
                <div class="row border">
                    <span class="col-4 border-end fw-bold">@lang('lang.birthdate') :</span>
                    <span class="col-4">{{ $etudiant->date_naissance }}</span>
                </div>
                @guest
                @else
                    @if(session('user')->id == $etudiant->id or session('user')->is_admin == 1)
                    <hr>
                   
                    <a href="{{route('etudiant.edit', $etudiant->id)}}" class="btn btn-outline-primary">@lang('lang.modify_info')</a>
                    <br><br>
                    <form id="delete-frm" action="{{ route('etudiant.destroy', $etudiant->id) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-danger">@lang('lang.delete_account')</button>
                    </form>
                    @endif
                @endguest
            </div>
        </div>
    </div>
@endsection