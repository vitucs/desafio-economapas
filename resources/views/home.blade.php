@extends('layouts.app')

@section('content')
<div class="principal">

    @if(session('success'))
    <div class="alert alert-success">
        {{session('success')}}
    </div>
    @endif
    @if(session('fail'))
    <div class="alert alert-danger">
        {{session('fail')}}
    </div>
    @endif
    <div class="card text-center">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="/home">Grupo Atual</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/groups">Grupos já Existentes</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="card" style="width: auto;">
                <div class="card-header titles">
                    Cidades
                </div>
                <div hidden>{{sort($cidades);}}</div>
                <form action="/home" method="POST">
                    @csrf
                    <label for="groupName" class="subtitles">Nome do Grupo</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control select-cities" name="groupName" id="groupName" aria-describedby="basic-addon3" placeholder="Ex. Grupo Nordeste">
                    </div>
                    <select class="form-select select-cities" aria-label="Default select example" name="cidade1">
                        <option selected value="">Selecione a Cidade 1</option>
                        @foreach ($cidades as $capitais)
                        <option>
                            {{$capitais['nome']}}
                        </option>
                        @endforeach
                    </select>
                    <select class="form-select select-cities" aria-label="Default select example" name="cidade2">
                        <option selected value="">Selecione a Cidade 2</option>
                        @foreach ($cidades as $capitais)
                        <option>
                            {{$capitais['nome']}}
                        </option>
                        @endforeach
                    </select>
                    <select class="form-select select-cities" aria-label="Default select example" name="cidade3">
                        <option selected value="">Selecione a Cidade 3</option>
                        @foreach ($cidades as $capitais)
                        <option>
                            {{$capitais['nome']}}
                        </option>
                        @endforeach
                    </select>
                    <select class="form-select select-cities" aria-label="Default select example" name="cidade4">
                        <option selected value="">Selecione a Cidade 4</option>
                        @foreach ($cidades as $capitais)
                        <option>
                            {{$capitais['nome']}}
                        </option>
                        @endforeach
                    </select>
                    <select class="form-select select-cities" aria-label="Default select example" name="cidade5">
                        <option selected value="">Selecione a Cidade 5</option>
                        @foreach ($cidades as $capitais)
                        <option>
                            {{$capitais['nome']}}
                        </option>
                        @endforeach
                    </select>
                    <input type="submit" class="btn btn-primary button-cities" value="Criar Novo Grupo" />
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
<script src="{{ asset("js/home/index.js") }}"></script>