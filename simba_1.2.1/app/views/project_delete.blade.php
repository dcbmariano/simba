@extends('layouts.master')

@section('conteudo')

<!-- Controle de erros -->
<center>
@if ( count($errors) > 0)
	<span class="label label-danger">Error: </span>
	<ul>
		@foreach($errors->all() as $e)
			<li>{{ $e }}</li>
		@endforeach
	</ul>
@endif

@if (isset($sucesso))
	<span class="label label-success">Success.</span>
@endif
</center>
<!-- End -->

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li><a href="{{ URL::to('projects') }}">Projects</a></li>
  <li class="active">Delete</li>
</ol>
<!-- Fim navegacao -->
<form role="form" method="POST" action="delete_confirm">
	<input type="hidden" name="id_project" value="{{ $project->id_project }}" />
	<h3>Confirm deletion of project?</h3>
	<br/>
	<a href="{{ URL::to('projects') }}" class="btn btn-default">Cancel</a>
	<input type="submit" value="Delete" class="btn btn-danger"/>
</form>

<div style="height:100px"></div>
<p style="text-align:center;background-color:#eee"><a href="{{URL::to('action/update_projects')}}">Update projects list</a> </p>

@stop