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
  <li class="active">Edit</li>
</ol>
<!-- Fim navegacao -->

<form role="form" method="POST">
	<input type="hidden" name="id_project" value="{{ $project->id_project }}" />
	<div class="form-group">
    	<label>Name organism:</label>
    	<input type="text" name="organism" class="form-control" value="{{ $project->organism_project }}" placeholder="Name Organism">
	</div>
	<div class="form-group">
    	<label>NGS:</label><br/>
    	<select name="ngs" class="form-control">
    		<option selected value="{{ $project->ngs_project }}">{{ $project->ngs_project }}</option>
    		<option value="iontor">Ion Torrent</option>
    		<option value="illumina">Illumina</option>
    		<option value="pacbio">Pacbio</option>
    		<option value="454">454 Roche</option>
    	</select>
    </div>
	<div class="form-group">
    	<label>Library</label>
    	<input type="text" name="library" class="form-control" value="{{ $project->library_project }}" placeholder="Library">
	</div>
	<br/>
	<input type="submit" name="submit" class="btn btn-danger" value="Submit" />
</form>

<div style="height:100px"></div>

@stop