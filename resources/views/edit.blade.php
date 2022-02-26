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
                    <a class="nav-link" href="/home">Grupo Atual</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/groups">Grupos já Existentes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active">Editar Grupo</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="card" style="width: auto;">
                <form action="/update/{{$group[0]->group}}" method="POST">
                    @csrf
                    @method('patch')
                    <div hidden>{{sort($cidades);}}</div>
                    <div class="card" style="width: auto; margin: 10px;">
                        <div class="card-header headerGroup">
                            <input type="text" value="{{$group[0]->groupName}}" style="text-align: center;" name="groupName" />
                        </div>
                        @foreach ($group as $content)
                        <select class="form-select select-cities" aria-label="Default select example" name="oldCity{{$loop->index+1}}">
                            <option value="{{$content->id}}_null">Selecione a Cidade {{$loop->index+1}}</option>
                            @foreach ($cidades as $capitais)
                            <option {{ $content->city == $capitais['nome']  ? 'selected' : '' }} value="{{$content->id}}_{{$capitais['nome']}}">
                                {{$capitais['nome']}}
                            </option>
                            @endforeach
                        </select>
                        @endforeach
                        @for ($i = count($group); $i < 5; $i++) <select class="form-select select-cities" aria-label="Default select example" name="newCity{{$i+1}}">
                            <option value="null_null">Selecione a Cidade {{$i+1}}</option>
                            @foreach ($cidades as $capitais)
                            <option value="newItem_{{ $capitais['nome'] }}">
                                {{$capitais['nome']}}
                            </option>
                            @endforeach
                            </select>
                            @endfor
                    </div>
                    <input type="submit" class="btn btn-primary button-cities" value="Atualizar Grupo" />
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
<script src="{{ asset("js/home/index.js") }}"></script>