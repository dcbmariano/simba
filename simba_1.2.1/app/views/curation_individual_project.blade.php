@extends('layouts.master')

@section('conteudo')

<div style="float:right;text-align:right;font-size:12px;font-family:arial">
	<p>
	<b>Organism: </b> <i>Corynebacterium pseudotuberculosis CP31</i><br/>
	<b>Reference: </b> <i>Corynebacterium pseudotuberculosis 52.97</i><br/>
	<b>Project name: </b> CP31<br/>
	<b>Date: </b> 12/04/2014
	<br/><br/></p>
</div>
<div class="clear:both"></div>
<p><a href="{{ URL::to('projects') }}">Projects</a> > <a href="{{ URL::to('projects/cp31') }}">cp31</a> > Test 1</p>
<br/>


<table class="table table-condensed table-striped table-bordered">
	<tr>
		<th>#</th>
		<th style="text-align:center" width="20">Status</th>
		<th>Action</th>
		<th>Gaps</th>
		<th style="text-align:center" width="120">Synteny chart</th>
		<th style="text-align:center" colspan="2" width="30">Download</th>
		<th style="text-align:center" width="20">Run</th>
		
	</tr>
	<tr>
		<td>1</td>
		<td style="text-align:center"><span title="OK" style="color:#009900" class="glyphicon glyphicon-ok"></span></td>
		<td>Set reference</td>
		<td>84 <a href="#warning1" data-toggle="modal" data-target="#warning1" style="color:#FF8000" title="WARNING: CLICK HERE TO SEE MORE">
				<span class="glyphicon glyphicon-exclamation-sign"></span>
			</a>
			<!-- Modal WARNING -->
			<div class="modal fade" id="warning1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
			    	<div class="modal-content">
			    		<div class="modal-header">
			        		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        		<h4 class="modal-title" id="myModalLabel">WARNING</h4>
			      		</div>
			    		<div class="modal-body" style="text-align:left">
			    			<p>1.3 kb were excluded, as new regions are not present in the reference. You need to solve a so manually. <a href="#">Download file</a>.
							</p>
			      		</div>
			      		<div class="modal-footer">
			        		<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			      		</div>
			    	</div><!-- /.modal-content -->
			  	</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			<!-- End -->
		</td>
		<td style="text-align:center">
			<a target="_blank" href="{{ URL::to('/') }}/tmp/gi_384546269_ref_NC_017337.1_.pdf" title="SYNTENY">
				<span class="glyphicon glyphicon-picture"></span>
			</a>
		</td>
		<td style="text-align:center">
			<a target="_blank" href="{{ URL::to('/') }}/tmp/o11.fasta" title="SCAFFOLDS">
				<span class="glyphicon glyphicon-stop"></span>
			</a>
		</td>
		<td style="text-align:center">
			<a target="_blank" href="{{ URL::to('/') }}/tmp/o11.fasta" title="CONTIGS">
				<span class="glyphicon glyphicon-align-justify"></span>
			</a>
		</td>
		<td style="text-align:center">
			<a href="{{ URL::to('/')}}/projects/cp31/1/F1" title="RUN">
				<span class="glyphicon glyphicon-chevron-right"></span>
			</a>
		</td>
		
	</tr>
	<tr>
		<td>2</td>
		<td style="text-align:center"><span title="Wait" style="color:#" class="glyphicon glyphicon-dashboard"></span></td>
		<td>Move dnaA</td>
		<td>84</td>
		<td style="text-align:center">
			
				<span class="glyphicon glyphicon-minus"></span>
			
		</td>
		<td style="text-align:center">
			
				<span class="glyphicon glyphicon-minus"></span>
			
		</td>
		<td style="text-align:center">
			
				<span class="glyphicon glyphicon-minus"></span>
		
		</td>
		<td style="text-align:center">
			<a href="{{ URL::to('/')}}/projects/cp31/1/F2" title="RUN">
				<span class="glyphicon glyphicon-chevron-right"></span>
			</a>
		</td>
	</tr>
	<tr>
		<td>3</td>
		<td style="text-align:center"><span title="Wait" style="color:#" class="glyphicon glyphicon-dashboard"></span></td>
		<td>Close gaps overlapping</td>
		<td>20</td>
		<td style="text-align:center">
			<span class="glyphicon glyphicon-minus"></span>
		</td>
		<td style="text-align:center">
			<span class="glyphicon glyphicon-minus"></span>
		</td>
		<td style="text-align:center">
			<span class="glyphicon glyphicon-minus"></span>
		</td>
		<td style="text-align:center">
			<a href="{{ URL::to('/')}}/projects/cp31/1/F3" title="RUN">
				<span class="glyphicon glyphicon-chevron-right"></span>
			</a>
		</td>
	</tr>
	<tr>
		<td>4</td>
		<td style="text-align:center"><span title="Wait" style="color:#" class="glyphicon glyphicon-dashboard"></span></td>
		<td>Analyze repetitive regions</td>
		<td>12</td>
		<td style="text-align:center">
			<span class="glyphicon glyphicon-minus"></span>
		</td>
		<td style="text-align:center">
			<span class="glyphicon glyphicon-minus"></span>
		</td>
		<td style="text-align:center">
			<span class="glyphicon glyphicon-minus"></span>
		</td>
		<td style="text-align:center">
			<a href="{{ URL::to('/')}}/projects/cp31/1/F4" title="RUN">
				<span class="glyphicon glyphicon-chevron-right"></span>
			</a>
		</td>
	</tr>
	<tr>
		<td>5</td>
		<td style="text-align:center"><span title="Wait" style="color:#" class="glyphicon glyphicon-dashboard"></span></td>
		<td>Validating with general mapping</td>
		<td>0</td>
		<td style="text-align:center">
			<span class="glyphicon glyphicon-minus"></span>
		</td>
		<td style="text-align:center">
			<span class="glyphicon glyphicon-minus"></span>
		</td>
		<td style="text-align:center">
			<span class="glyphicon glyphicon-minus"></span>
		</td>
		<td style="text-align:center">
			<a href="{{ URL::to('/')}}/projects/cp31/1/F5" title="RUN">
				<span class="glyphicon glyphicon-chevron-right"></span>
			</a>
		</td>
	</tr>
</table>

<div style="height:100px"></div>
<p style="text-align:center;background-color:#eee"><b>Note: </b>Pipeline to fragment data. AMW uses <a target="_blank" href="#">CONTIGuator</a> to generate <i>scaffolds</i>.</p>

@stop