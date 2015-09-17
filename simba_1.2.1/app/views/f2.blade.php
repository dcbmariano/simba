@extends('layouts.master')
@section('conteudo')
<!-- Insira o conteudo da pagina aqui -->

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li><a href="{{ URL::to('projects') }}">Projects</a></li>
  <li><a href="{{ URL::to('projects') }}/{{ $project->id_project }}">{{ $project->name_project }}</a></li>  
  <li><a href="{{ URL::to('projects')}}/{{ $project->id_project }}/assemblies/{{ $assembly->id_assembly }}">Trial {{ $assembly->version_assembly }}</a></li>
  <li class="active">Step 2</li>
</ol>
<!-- Fim navegacao -->

<div style="float:right;text-align:right;font-size:12px;font-family:arial">
	<p>
	<b>Organism: </b> <i>{{ $project->organism_project }}</i><br/>
	<b>Date: </b> {{ $project->created_at }}
	<br/><br/></p>
</div>

<div class="clear:both"></div>

<h3><B>Correcting the beginning of the file by dnaA gene</B></h3>
<br/>
<div class="alert alert-warning">
	At this stage we fix the sequence so that it begins by dnaA gene. 
A cut will be made in the "sequence" in the start position of the first gene. The new contig formed will be moved to the beginning of the genome.
</div>
<br/><center>
<a href="{{ URL::to('projects') }}/{{ $project->id_project }}/assemblies/{{ $assembly->id_assembly }}/F2/run" class="btn btn-success">Run movednaA.py and CONTIGuator</a>
<br/><br/>or<br/><br/>
<form method="POST" action="F2/run">
	<input type="hidden" name="skip" value="skip" />
	<input type="submit" name="submit" value="SKIP" class="btn btn-danger" />
</form>
</center>

<div style="height:50px"></div>
<div class="alert alert-info" style="text-align:center">
	<b>Note: </b>SIMBA uses <a href="//github.com/dcbmariano/scripts">moveDNAA</a> to this step.
</div>
<!-- End -->
@stop