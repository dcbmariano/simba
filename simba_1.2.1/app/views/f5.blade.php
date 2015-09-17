@extends('layouts.master')
@section('conteudo')
<!-- Insira o conteudo da pagina aqui -->

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li><a href="{{ URL::to('projects') }}">Projects</a></li>
  <li><a href="{{ URL::to('projects') }}/{{ $project->id_project }}">{{ $project->name_project }}</a></li>  
  <li><a href="{{ URL::to('projects')}}/{{ $project->id_project }}/assemblies/{{ $assembly->id_assembly }}">Trial {{ $assembly->version_assembly }}</a></li>
  <li class="active">Step 5</li>
</ol>
<!-- Fim navegacao -->

<div style="float:right;text-align:right;font-size:12px;font-family:arial">
	<p>
	<b>Organism: </b> <i>{{ $project->organism_project }}</i><br/>
	<b>Date: </b> {{ $project->created_at }}
	<br/><br/></p>
</div>

<div class="clear:both"></div>

<br/>

<h2><b>Statistics</b></h2>

<table class="table">
	<tr>
		<td rowspan="2">
			<h4><b>Undefined nucleotides</b></h4>
			<table class="table table-striped table-bordered table-condensed">
				<tr>
					<th>Nucleotide</th>
					<th style="text-align: center">Quant. (F4)</th>
					<th style="text-align: center">Quant. (F5)</th>
				</tr>
				<tr>
					<td>R:	Purine (A or G)</td>
					<td style="text-align: center">{{ $nucleotideos[0] }}</td>
					<td style="text-align: center"><?php if(isset($nucleotideos5)) echo $nucleotideos5[0]; else echo '-'; ?></td>
				</tr>
				<tr><td>Y:	Pyrimidine (C, T, or U)</td>
					<td style="text-align: center">{{ $nucleotideos[1] }}</td>
					<td style="text-align: center"><?php if(isset($nucleotideos5)) echo $nucleotideos5[1]; else echo '-'; ?></td>
				</tr>
				<tr>
					<td>M:	C or A</td>
					<td style="text-align: center">{{ $nucleotideos[2] }}</td>
					<td style="text-align: center"><?php if(isset($nucleotideos5)) echo $nucleotideos5[2]; else echo '-'; ?></td>
				</tr>
				<tr>
					<td>K:	T, U, or G</td>
					<td style="text-align: center">{{ $nucleotideos[3] }}</td>
					<td style="text-align: center"><?php if(isset($nucleotideos5)) echo $nucleotideos5[3]; else echo '-'; ?></td>
				</tr>
				<tr>
					<td>W:	T, U, or A</td>
					<td style="text-align: center">{{ $nucleotideos[4] }}</td>
					<td style="text-align: center"><?php if(isset($nucleotideos5)) echo $nucleotideos5[4]; else echo '-'; ?></td>
				</tr>
				<tr>
					<td>S:	C or G</td>
					<td style="text-align: center">{{ $nucleotideos[5] }}</td>
					<td style="text-align: center"><?php if(isset($nucleotideos5)) echo $nucleotideos5[5]; else echo '-'; ?></td>
				</tr>
				<tr>
					<td>B:	C, T, U, or G (not A)</td>
					<td style="text-align: center">{{ $nucleotideos[6] }}</td>
					<td style="text-align: center"><?php if(isset($nucleotideos5)) echo $nucleotideos5[6]; else echo '-'; ?></td>
				</tr>
				<tr>
					<td>D:	A, T, U, or G (not C)</td>
					<td style="text-align: center">{{ $nucleotideos[7] }}</td>
					<td style="text-align: center"><?php if(isset($nucleotideos5)) echo $nucleotideos5[7]; else echo '-'; ?></td>
				</tr>
				<tr>
					<td>H:	A, T, U, or C (not G)</td>
					<td style="text-align: center">{{ $nucleotideos[8] }}</td>
					<td style="text-align: center"><?php if(isset($nucleotideos5)) echo $nucleotideos5[8]; else echo '-'; ?></td>
				</tr>
				<tr>
					<td>V:	A, C, or G (not T, not U)</td>
					<td style="text-align: center">{{ $nucleotideos[9] }}</td>
					<td style="text-align: center"><?php if(isset($nucleotideos5)) echo $nucleotideos5[9]; else echo '-'; ?></td>
				</tr>	
				<tr>
					<td>N:	Any base (A, C, G, T, or U)</td>
					<td style="text-align: center">{{ $nucleotideos[10] }}</td>
					<td style="text-align: center"><?php if(isset($nucleotideos5)) echo $nucleotideos5[10]; else echo '-'; ?></td>
				</tr>
			</table>
		</td>
		<td>
			<h4><b>F4 (<a target="_blank" href="{{ URL::to('/') }}/tmp/f4_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.fasta">download</a>) | M4 (<a target="_blank" href="{{ URL::to('/') }}/tmp/m4_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.fasta">download</a>) </b></h4>
			<pre>{{ $f4_statistics }}</pre>
		</td>
		<td>
			<h4><b>Excluded (<a target="_blank" href="{{ URL::to('/') }}/tmp/excluded_a{{ $assembly->id_assembly }}.fasta">download</a>)</b></h4>
			<pre>{{ $exc_f4_statistics }}</pre>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<h4><b>FINAL VERSION: F5 (<a target="_blank" href="{{ URL::to('/') }}/tmp/f5_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.fasta">download</a>) | M5 (<a target="_blank" href="{{ URL::to('/') }}/tmp/m5_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.fasta">download</a>) </b></h4>
			<pre><?php if(isset($f5_statistics)) echo $f5_statistics; else echo 'F5 file not created yet.'; ?></pre>
		</td>
	</tr>	
</table>

<h2><b>Manual curation</b></h2>
<table class="table">
	<tr>
		<td>
			<?php echo Form::open(array('url' => "projects/$project->id_project/assemblies/$assembly->id_assembly/F5/run", 'files' => true)); ?>
			<div class="alert alert-warning" id="help">
				You can download version 4 (M4 or F4) of genome and use external software for curation. 
				After removing all the gaps, submit the file to SIMBA generate graphs of synteny.
			</div>
			<br/>
			<input type="file" name="f5"/>
			<!--<br/>
			<B>or</B>
			<br/><br/>
			<span><input type="checkbox" name="transfer_f4" value="1" /> <label>I do not want to make changes to the file F4. Just download information from F4 to F5.</span> 
			<p style="font-size: 11px; color:#BB0000; font-weight: bold">Warning: it is must there are no gaps, nor undefined nucleotides in F4 file.</p></label>
			-->
			<br/><br/>
			<input type="submit" class="btn btn-success" value="Send file and finish curation"/>
		</td>
	</tr>
</table>

<div style="height:50px"></div>
<div class="alert alert-info" style="text-align:center">
	<b>Note: </b>Next step is prediction of ORFs. We recomended use RAST and Dinnotator!
</div>

<!-- End -->
@stop