@extends('layouts.master')
@section('conteudo')
<!-- Insira o conteudo da pagina aqui -->

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li><a href="{{ URL::to('tools') }}">Tools</a></li>
  <li class="active">LegoScaffold</li>
</ol>
<!-- Fim navegacao -->

<h1>LegoScaffold</h1>

@stop