@extends('layouts.master')

@section('conteudo')

<a href="#" class="btn btn-success">New project</a>

<br/>
<a href="{{ URL::to('task') }}">Testar acesso e inserção ao banco de dados</a>

@stop