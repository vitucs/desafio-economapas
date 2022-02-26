@extends('layouts.app')

@section('content')
<div class="principal">

    @if(Session::get('success'))
    <div class="alert alert-success">
        {{Session::get('success')}}
    </div>
    @endif
    @if(Session::get('fail'))
    <div class="alert alert-danger">
        {{Session::get('fail')}}
    </div>
    @endif
    <div class="card text-center">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="/home">Grupo Atual</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="/groups">Grupos já Existentes</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="card" style="width: auto;">
                <div class="card-header titles">
                    Grupos Criados
                </div>
                @foreach ($groups as $group)
                <div class="card" style="width: auto; margin: 10px;">
                    <div class="card-header headerGroup">
                        {{$group[0]->groupName}}
                        <div class="buttons">
                            <form action='/edit/{{$group[0]->group}}' class="formButton" method="POST">
                                @csrf 
                                @method('get')
                                <input type="submit" class="btn btn-primary" value="Editar Grupo" />
                            </form>
                            <form action='/delete/{{$group[0]->group}}' class="formButton" method="POST">
                                @csrf 
                                @method('delete')
                                <input type="submit" class="btn btn-danger" value="Excluir Grupo" />
                            </form>
                        </div>
                    </div>
                    @foreach ($group as $content)
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">{{$content->city}}</li>
                    </ul>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

@endsection
<script src="{{ asset("js/home/index.js") }}"></script>