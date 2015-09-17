@extends('layouts.master')
@section('conteudo')
<!-- Insira o conteudo da pagina aqui -->

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li><a href="{{ URL::to('projects') }}">Projects</a></li>
  <li><a href="{{ URL::to('projects') }}/{{ $project->id_project }}">{{ $project->name_project }}</a></li>  
  <li><a href="{{ URL::to('projects')}}/{{ $project->id_project }}/assemblies/{{ $assembly->id_assembly }}">Trial {{ $assembly->version_assembly }}</a></li>
  <li class="active">Step 1</li>
</ol>
<!-- Fim navegacao -->

<div style="float:right;text-align:right;font-size:12px;font-family:arial">
	<p>
	<b>Organism: </b> <i>{{ $project->organism_project }}</i><br/>
	<b>Date: </b> {{ $project->created_at }}
	<br/><br/></p>
</div>
<br/>
<div class="clear:both"></div>

<!-- START -->
<div class="tabbable tabs-left">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#lA" data-toggle="tab">Scaffolding by reference</a></li>
		<li class=""><a href="#lB" data-toggle="tab">Scaffolding by optical mapping</a></li>
	</ul>
	
	<div class="tab-content">
		<div class="tab-pane active" id="lA">
<!-- END-->		
			<h3><b>Scaffolding by Reference</b></h3>
			
			<!-- Setor de erros -->
			@if ( count($errors) > 0)
				<br/>
				<div class="alert alert-danger">
					@foreach($errors->all() as $e)
						<p style="padding:5px">{{ $e }}</p>
					@endforeach
				</div>
			@endif
				
			@if (isset($sucesso))
				<span class="label label-success">Success.</span>	
			@endif
			<!-- End -->
			
			<br/>
			<div class="alert alert-warning">
				In this step you need to enter the address of the file with complete genome (fasta) and file with information about genes (GenBank) of an organism phylogenetically close. They will be important for ordering of contigs. 
				<br/><br/><b>How to get the fasta and genbank files?</b> 
				<br/>By the NCBI FTP: <a target="_blank" href="ftp://ftp.ncbi.nih.gov/genomes/Bacteria/">ftp://ftp.ncbi.nih.gov/genomes/Bacteria/</a>
			</div>
			<br/>
			<form method="post" action="F1/run">
				<input type="hidden" name="optical_mapping" value="FALSE" />
				<input type="hidden" value="F1" name="step" /> 
				<input type="hidden" value="{{ $assembly->version_assembly }}" name="trial" />
				<label>Fasta file</label>
				<input type="text" name="fna" placeholder="Type the full link. E.g.: ftp://ftp.ncbi.nih.gov/genomes/Bacteria/Corynebacterium_pseudotuberculosis_1002_uid159677/NC_017300.fna" class="form-control" />
				<br/>
				<label>Genbank file</label>
				<input type="text" name="gbk" placeholder="Type the full link. E.g.: ftp://ftp.ncbi.nih.gov/genomes/Bacteria/Corynebacterium_pseudotuberculosis_1002_uid159677/NC_017300.gbk" class="form-control" />
				<br/>
				<center><input type="submit" value="Go!" class="btn btn-success col-lg-1"></center>
			
			</form>
			
			<p><br/><br /><BR/><br/></p>
			<div class="alert alert-info" style="text-align:center">
				<b>Note: </b>We will generate a new align of the contigs with the reference genome.
			</div>
			
			<!-- START -->
		</div>
		<div class="tab-pane" id="lB">
			<h3><b>Scaffolding by optical mapping</b></h3>
			<br/>
			<div class="alert alert-warning">
				<b>Input optical mapping report</b>.
			</div>
			<form method="post" action="F1/run">
				<input type="hidden" value="{{ $assembly->version_assembly }}" name="trial" />
				<input type="hidden" name="optical_mapping" value="TRUE" />
				<textarea class="form-control" rows="7" name="optical_mapping_value"></textarea>
				<br/>
				<center><input type="submit" value="Go!" class="btn btn-success col-lg-1"></center>
			</form>
			
			<p><br/><br /><BR/><br/></p>
			<div class="alert alert-info" style="text-align:center">
				<b>Note: </b>We will generate a new align of the contigs with the optical mapping.
			</div>
		</div>
	</div>
</div>
<!-- END -->
<!-- End -->
@stop