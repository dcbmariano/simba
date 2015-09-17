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
  <li><a href="{{ URL::to('projects') }}/{{ $project->id_project }}">{{ $project->name_project }}</a></li>
  <li class="active">Delete trial</li>
</ol>
<!-- Fim navegacao -->
<form role="form" method="POST" action="{{ URL::to('projects') }}/{{ $project->id_project }}/delete_confirm_assembly/{{ $assembly->id_assembly }}">
	<input type="hidden" name="id_assembly" value="{{ $assembly->id_assembly }}" />
	<h3>Confirm deletion of this assembly trial?</h3>
	<br/>
	<a href="{{ URL::to('projects') }}/{{ $project->id_project }}/assemblies" class="btn btn-default">Cancel</a>
	<input type="submit" value="Delete" class="btn btn-danger"/>
</form>

<div style="height:100px"></div>
<p style="text-align:center;background-color:#eee"><a href="{{URL::to('action/update_projects')}}">Update projects list</a> </p>

@stop