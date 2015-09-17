@extends('layouts.master')
@section('conteudo')
<!-- Insira o conteudo da pagina aqui -->

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li><a href="{{ URL::to('tools') }}">Tools</a></li>
  <li class="active">Web CONTIGuator</li>
</ol>
<!-- Fim navegacao -->

<h1>Web CONTIGuator RESULT</h1>

<img src="{{ URL::to('/') }}/tmp/webcontiguator/webcontiguator.png" width="100%"/>
<a href="{{ URL::to('/') }}/tmp/webcontiguator/webcontiguator.pdf" target="_blank" class="btn btn-default">Full image (PDF)</a>
<a href="{{ URL::to('/') }}/tmp/webcontiguator/webcontiguator.fsa" target="_blank" class="btn btn-default">Fasta</a>

@stop