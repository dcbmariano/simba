@extends('layouts.master')
@section('conteudo')
<!-- Insira o conteudo da pagina aqui -->

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li class="active">Docs</li>
</ol>
<!-- Fim navegacao -->

<a href="{{ URL::to('/') }}/documentation.pdf">Download SIMBA docs</a>

<!-- End -->
@stop
