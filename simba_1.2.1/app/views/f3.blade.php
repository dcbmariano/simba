@extends('layouts.master')
@section('conteudo')
<!-- Insira o conteudo da pagina aqui -->

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li><a href="{{ URL::to('projects') }}">Projects</a></li>
  <li><a href="{{ URL::to('projects') }}/{{ $project->id_project }}">{{ $project->name_project }}</a></li>  
  <li><a href="{{ URL::to('projects')}}/{{ $project->id_project }}/assemblies/{{ $assembly->id_assembly }}">Trial {{ $assembly->version_assembly }}</a></li>
  <li class="active">Step 3</li>
</ol>
<!-- Fim navegacao -->

<div style="float:right;text-align:right;font-size:12px;font-family:arial">
	<p>
	<b>Organism: </b> <i>{{ $project->organism_project }}</i><br/>
	<b>Date: </b> {{ $project->created_at }}
	<br/><br/></p>
</div>

<div class="clear:both"></div>

<h3><B>Building Supercontigs </B><a style="font-size: 16px" href="#help"><span class="glyphicon glyphicon-question-sign"></span></a></h3>
<br/>

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

<img id="myimage" src="{{ URL::to('/') }}/tmp/f2_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.png" width="100%" />

<div style="text-align:center;">
	<span class="label label-info" style="border:1px #000099 solid">No have overlaps</span>
	<span class="label label-warning" style="border:1px #990000 solid">One overlap</span>
	<span class="label label-danger" style="border:1px #990000 solid">Two overlaps</span>
</div>

<div class="clear:both"></div>
<br/><br/>
<table class="table table-condensed table-striped table-bordered">
	<tr>
		<th style="text-align:center" width="50">Gap</th>
		<th style="text-align:center">Contig Left</th>
		<th style="text-align:center">Contig Right</th>
		<th style="text-align:center" width="140">Is there overlap?</th>
		<th style="text-align:center" width="80">Action</th>
	</tr>
	<?php 
		$num_blast = count($blast);
		for($i = 0; $i < $num_blast; $i++){
			$j = $i*2;
	?>
	<tr>
		<td style="text-align:center"><b>{{ $i+1 }}</b></td>
		<td style="text-align:center"><?php $contig_list2[$j] = str_replace('>c','C',$contig_list[$j]); print $contig_list2[$j] = str_replace('_',' ',$contig_list2[$j]); ?></td>
		<td style="text-align:center"><?php $contig_list2[$j+1] = str_replace('>c','C',$contig_list[$j+1]); print $contig_list2[$j+1] = str_replace('_',' ',$contig_list2[$j+1]); ?></td>
		<td style="text-align:center">
			<?php if(substr_count($blast[$i], "\n") > 30){ ?>
				<span class="glyphicon glyphicon-ok" style="color:#009900"></span>
			<?php } else { ?>
				<span title="OK" style="color:#990000" class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<a href="#g{{ $i+1 }}" data-toggle="modal" data-target="#g{{ $i+1 }}">
				BLAST
			</a>
			<!-- Modal Result BLAST -->
			<div class="modal fade" id="g{{ $i+1 }}" style="width:100%; " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  >
				<div class="modal-dialog" style="width: 70%">
			    	<div class="modal-content">
			    		<div class="modal-header">
			        		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        		<h4 class="modal-title" id="myModalLabel"><b><span style="color:#FF0000">Blast:</span> {{ $contig_list2[$j] }} <span style="color:#FF0000">x</span> {{ $contig_list2[$j+1] }}</b></h4>
			      		</div>
			    		<div class="modal-body" style="text-align:left">
							<pre>								
								{{ $blast[$i] }}
							</pre>
							<!--<center><br/>
								<h4><b>Understanding what will be done at this stage</b></h4>
								<img src="{{ URL::to('/') }}/img/cut.png" />
							</center>-->
			    		</div>
			      		<div class="modal-footer">
			      			<form class="form-inline" action="F3/run_part" method="POST">
			      				<label>Lenght (Leave blank if the value is equal to 3000): </label><br/>
			      				<input type="text" name="length_query" class="form-control" placeholder="Length query">
							    <input type="text" name="length_subject" class="form-control" placeholder="Length subject">
							   	<br/><br/>
							   	<label>Cutting positions: </label><br/>
			      				<input type="hidden" name="contig_right" value='{{ str_replace("\n","",$contig_list[$j+1]) }}' />
							    <input type="text" name="cut_right" class="form-control" placeholder="Cutting query - contig right (E.g.: 139)">
							    <input type="text" name="cut_left" class="form-control" placeholder="Cutting subject - contig left (E.g.: 5000)">
							    <br/><br/>
							    <button type="submit" class="btn btn-danger">Cut</button>
							    <button type="button" class="btn" data-dismiss="modal">Cancel</button>
							</form>			        	
			      		</div>
			    	</div><!-- /.modal-content -->
			  	</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			<!-- End -->
		</td>
	</tr>
	<?php } ?>
</table>

<p style="text-align: right">
	<a href="{{ URL::to('projects') }}/{{ $project->id_project }}/assemblies/{{ $assembly->id_assembly }}/F3/run" class="btn btn-success">Save updates in database</a>
	<br/><br/>or<br/>
	<form method="POST" action="F3/run" style="text-align: right">
		<input type="hidden" name="skip" value="skip" />
		<input type="submit" name="submit" value="SKIP" class="btn btn-danger" />
	</form>
</p>
<br/><br/>

<div class="alert alert-warning" id="help">
	In this step we will close gaps in overlapping regions between neighboring contigs to build super contigs. 
	View the alignments using BLAST and send cutting positions to SIMBA can do the processing and closing of the gap.
	<b>Understanding what will be done at this stage:</b><br/><br/>
	<center><img src="{{ URL::to('/') }}/img/cut2.png" /></center><br/>
	Tip: If there is overlap, click "Blast". You can set breakpoints, but we recommend choosing the last two numbers (the latter referring to "subject" and the latter referring to "query"). Finally, click "cut". <a href="#myimage">Start!</a><br/><br/>
	<b>Important:</b> save changes to the database only when all editing is completed (stay tuned to "Is there overlap?" column).
</div>
<div style="height:80px"></div>
<div class="alert alert-info" style="text-align:center">
	<b>Note: </b>SIMBA uses Local <a href="//blast.ncbi.nlm.nih.gov/Blast.cgi">BLAST</a> to discover overlaps among contigs and <a href="//www.dynamicdrive.com">Image Power Zoomer (c) Dynamic Drive DHTML code library</a> to zoom tool.
</div>

<!-- End -->
@stop