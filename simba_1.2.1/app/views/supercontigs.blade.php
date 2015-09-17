@extends('layouts.master')
@section('conteudo')
<!-- Insira o conteudo da pagina aqui -->

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li><a href="{{ URL::to('tools') }}">Tools</a></li>
  <li class="active">Supercontigs Constructor</li>
</ol>
<!-- Fim navegacao -->

<h1>Supercontigs Constructor</h1> 

@stop