@extends('layouts.master')

@section('conteudo')

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li><a href="{{ URL::to('projects') }}">Projects</a></li>
  <li><a href="{{ URL::to('projects') }}/{{ $project->id_project }}">{{ $project->name_project }}</a></li>  
  <li class="active">New quast</li>
</ol>
<!-- Fim navegacao -->

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

<!-- Formulario nova montagem -->
<form role="form" method="POST" action="../run_new_quast" enctype="multipart/form-data">
	
	<div class="tabbable tabs-left">
		<ul class="nav nav-tabs">
			<li><a href="#lA" data-toggle="tab">New Quast analysis</a></li>
			<li class="active"><a href="#lB" data-toggle="tab">Quast results</a></li>
		</ul>
		
		<div class="tab-content">
			<div class="tab-pane" id="lA">
				<!-- Formulario default -->

				<br/><br/>
				<label class="label label-primary">Number of threads</label>
				<input type="text" name="quast_processors" placeholder="E.g.: 12 (Default is 1)" class="form-control" />


				<br/><br/>
				<label class="label label-primary"><?php
				$out = "Reference fasta file";
				if (is_file($folder."/quast_fasta.fasta")){
					$out .= " (or left in blank to use the last uploaded file)";
				}
				echo $out;
				?>
				</label>
				<input type="file" name="quast_fasta" placeholder="" class="form-control" />

				<br/><br/>
				<label class="label label-primary"><?php
				$out = "Reference gff file";
				if (is_file($folder."/quast_gff.gff")){
					$out .= " (or left in blank to use the last uploaded file)";
				}
				echo $out;
				?>
				</label></label>
				<input type="file" name="quast_gff" placeholder="" class="form-control" />

				<br /><br />
				<input type="submit" name="submit" class="btn btn-success" value="Click to run quast with default parameters" />
				<!-- End -->
			</div>
	
			<div class="tab-pane active" id="lB">
				<br />
				<?php
					if (is_dir($folder."/quast_results")){
						$results = array();
						if ($dh = opendir($folder."/quast_results")) {
							while (($file = readdir($dh)) !== false) {
								$file1 = $folder."/quast_results/".$file;
								if ($file1 != $folder."/quast_results/." && $file1 != $folder."/quast_results/.." && $file1 != $folder."/quast_results/latest" && is_dir($file1)){
									$results[] = array($file, $file1);
								}
							}
							closedir($dh);
						}
						if (count($results)>0){
							sort($results);
							foreach ($results as $result){
								echo "<a href='../../../".$result[1]."/report.html' target='_blank'>".$result[0]."</a><br />";
							}
						} else {
							echo "No results were found.";
						}
					} else {
						echo "No results were found.";
					}
				?>
			</div>
		</div>
	</div>
</form>
<!-- End -->
	

<div style="height:50px"></div>
<div class="alert alert-info" style="text-align:center">
	<b>Note: </b>SIMBA uses <a target="_blank" href="...">QUAST</a> to analyze information about results of assemblies.
</div>

@stop