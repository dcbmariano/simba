@extends('layouts.master')
@section('conteudo')
<!-- Insira o conteudo da pagina aqui -->

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li><a href="{{ URL::to('projects') }}">Projects</a></li>
  <li><a href="{{ URL::to('projects') }}/{{ $project->id_project }}">{{ $project->name_project }}</a></li>  
  <li><a href="{{ URL::to('projects')}}/{{ $project->id_project }}/assemblies/{{ $assembly->id_assembly }}">Trial {{ $assembly->version_assembly }}</a></li>
  <li class="active">Step 4</li>
</ol>
<!-- Fim navegacao -->

<div style="float:right;text-align:right;font-size:12px;font-family:arial">
	<p>
	<b>Organism: </b> <i>{{ $project->organism_project }}</i><br/>
	<b>Date: </b> {{ $project->created_at }}
	<br/><br/></p>
</div>

<div class="clear:both"></div>

<h3><B>Mapping repetitive regions </B><a style="font-size: 16px" href="#help"><span class="glyphicon glyphicon-question-sign"></span></a></h3>
<br/>

<div style="text-align:center;">
	<span class="label label-primary">Operon rRNA</span>
	<span class="label label-info">Transposon</span>
	<span class="label label-success">Phage</span>
	<span class="label label-warning">Plasmid</span>
</div>

<!-- ZOOM IMAGEM -->
<script src="{{ URL::to('/') }}/js/jquery.js"></script>
<script type="text/javascript" src="{{ URL::to('/') }}/js/ddpowerzoomer.js">
	/***********************************************
	* Image Power Zoomer- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
	* This notice MUST stay intact for legal use
	* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
	***********************************************/
</script>
<script type="text/javascript">
	jQuery(document).ready(function($){ //fire on DOM ready
	 $('#myimage').addpowerzoom({ defaultpower:3,powerrange: [2, 3],magnifiersize: [280, 280]})
	})
</script>
<!-- FIM ZOOM -->

<img id="myimage" src="{{ URL::to('/') }}/tmp/f3_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.png" width="100%" />
<div class="clear:both"></div>

<br/><br/>

<table class="table table-condensed table-striped table-bordered">
	<tr>
		<th style="text-align:center" width="50">Gap</th>
		<th style="text-align:center">Contig Left</th>
		<th style="text-align:center">Contig Right</th>
		<th style="text-align:center" width="80">Action</th>
	</tr>
	<?php 
		$num_contig = count($contig_list);
		for($i = 0; $i < $num_contig-1; $i++){
	?>
	<tr>
		<td style="text-align:center"><b>{{ $i+1 }}</b></td>
		<td style="text-align:center"><?php $contig_list2[$i] = str_replace('>c','C',$contig_list[$i]); print $contig_list2[$i] = str_replace('_',' ',$contig_list2[$i]); ?></td>
		<td style="text-align:center"><?php $contig_list2[$i+1] = str_replace('>c','C',$contig_list[$i+1]); print $contig_list2[$i+1] = str_replace('_',' ',$contig_list2[$i+1]); ?></td>
		
		<td style="text-align:center">
			<form action="F4/run_part" method="POST">
				<input type="hidden" name="contig_left" value="{{ $contig_list[$i] }}" />
				<input type="hidden" name="contig_right" value="{{ $contig_list[$i+1] }}" />
				<input type="submit" value="map" class="btn btn-sm"/>
			</form>		
		</td>
	</tr>
	<?php } ?>
</table>

<BR/>
<p style="text-align: right">
	<a href="{{ URL::to('projects') }}/{{ $project->id_project }}/assemblies/{{ $assembly->id_assembly }}/F4/run" class="btn btn-success">Save updates in database</a>
	<br/><br/>or<br/>
	<form method="POST" action="F4/run" style="text-align: right">
		<input type="hidden" name="skip" value="skip" />
		<input type="submit" name="submit" value="SKIP" class="btn btn-danger" />
	</form>
</p>
<br/><br/>
<hr/>
<br/><br/>
<div class="alert alert-warning" id="help">
	In this step, Simba uses internal scripts to create bookmarks and make rapid surveys using Mira (average time 10-20 minutes). Finally, it uses the mapping to close the gap. <b>Simba works in 4 phases:</b><br/>
	<center><br/><img src="{{ URL::to('/') }}/img/mapgap.jpg" /><p style="font-size:11px;color:#ccc">Figure legend is in portuguese (PT-BR).</p></center>
	<a href="#myimage">Start!</a><br/><br/>
	<b>Important:</b> Use the graph of the genome synteny. Close only possible repetitive regions, such as rRNA operon, transposons, phages and plasmids. Stay tuned to the colors that represent each repetitive element!
</div>

<div style="height:50px"></div>
<div class="alert alert-info" style="text-align:center">
	<b>Note: </b>SIMBA uses <a href="//github.com/dcbmariano/scripts">mapRepeat</a> and <a href="//mira-assembler.sourceforge.net/docs/DefinitiveGuideToMIRA.html">Mira</a> to map reads. <a href="//www.dynamicdrive.com">Image Power Zoomer (c) Dynamic Drive DHTML code library</a> is used to zoom tool.
</div>

<!-- End -->
@stop