@extends('layouts.master')
@section('conteudo')
<!-- Insira o conteudo da pagina aqui -->

{{ Form::open ( array("action" => "TaskController@postAdd"))}}
	<label>Tarefa a ser cumprida: </label>
	<input type="text" name="titulo" />
	<input type="submit" value="Enviar" class="btn btn-danger" />
{{ Form::close() }}

<!-- End -->
@stop