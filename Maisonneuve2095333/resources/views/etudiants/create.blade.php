@extends('layouts.app')
@section('content')
   
<div class="container">
        <div class="row">
            <div class="col-12 pt-2">
                <a href="{{url()->previous()}}" class="btn btn-outline-primary btn-sm">@lang('lang.return') </a>
                <div class="border rounded mt-5 pl-4 pr-4 pt-4 pb-4">
                    <h1 class="display-4 text-center">@lang('lang.add_student_title')</h1>

                    <form action="{{route('etudiant.store')}}" method="POST">
                        @csrf
                        <div class="row p-4">
                            <div class="control-group col-12">
                                <label for="nom">@lang('lang.name') :</label>
                                <input type="text" id="nom" class="form-control" name="nom"
                                       placeholder="@lang('lang.student_name') " required value="{{old('nom')}}">
                                @if ($errors->has('nom'))
                                <span class="text-danger">{{ $errors->first('nom') }}</span>
                                @endif
                            </div>
                            <div class="control-group col-12 mt-2">
                                <label for="adresse">@lang('lang.address') :</label>
                                <input type="text" id="adresse" class="form-control" name="adresse"
                                       placeholder="@lang('lang.student_address') " required required value="{{old('adresse')}}">
                                 @if ($errors->has('adresse'))
                                <span class="text-danger">{{ $errors->first('adresse') }}</span>
                                @endif
                            </div>
                            <div class="control-group col-12 mt-2">
                                <label for="phone">@lang('lang.phone') :</label>
                                <input type="text" id="phone" class="form-control" name="phone"
                                       placeholder="@lang('lang.student_phone') " required value="{{old('phone')}}">
                                @if ($errors->has('phone'))
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                                @endif
                            </div>
                            <div class="control-group col-12 mt-2">
                                <label for="email">@lang('lang.email') :</label>
                                <input type="text" id="email" class="form-control" name="email"
                                       placeholder="@lang('lang.student_email') " required value="{{old('email')}}">
                                @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="control-group col-12 mt-2">
                                <label for="password">@lang('lang.password') :</label>
                                <input type="password" placeholder="@lang('lang.student_password')" id="password" class="form-control"
                                    name="password" required>
                                @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            <div class="control-group col-12 mt-2">
                                <label for="date_naissance">@lang('lang.birthdate') :</label>     
                                <input id="date_naissance" class="form-control" name="date_naissance" readonly style="background-color: white;"
                                       placeholder="@lang('lang.student_birthdate') " required value="{{old('date_naissance')}}">
                                @if ($errors->has('date_naissance'))
                                <span class="text-danger">{{ $errors->first('date_naissance') }}</span>
                                @endif
                            </div>
                            <div class="control-group col-12 mt-2">
                                <label for="ville_id">@lang('lang.city') :</label>
                                <select id="ville_id" class="form-control" name="ville_id" required >     
                                    <option disabled selected>Choisir la ville de l'étudiant</option>
                                    @foreach($villes as $ville)
                                    <option {{old('ville_id') == $ville->id ? "selected" : ""}} value="{{ $ville->id }}">{{ $ville->nom }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('ville_id'))
                                <span class="text-danger">{{ $errors->first('ville_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="control-group col-12 text-center">
                                <button id="btn-submit" class="btn btn-primary">
                                    @lang('lang.save')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection