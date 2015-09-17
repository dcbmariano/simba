@extends('layouts.master')

@section('conteudo')

<div style="float:right;text-align:right;font-size:12px;font-family:arial">
	<p>
	<b>Organism: </b> <i>Corynebacterium pseudotuberculosis CP31</i><br/>
	<b>Project name: </b> CP31<br/>
	<b>Date: </b> 12/04/2014
	<br/><br/></p>
</div>
<div class="clear:both"></div>
<p><a href="{{ URL::to('projects') }}">Projects</a> > cp31</p>
<br/>
<a href="#" class="btn btn-success">New test</a>
<br/><br/>
<table class="table table-condensed table-striped table-bordered">
	<tr>
		<th>#</th>
		<th>Number contigs</th>
		<th>Lenght genome</th>
		<th>Min contig</th>
		<th>Max contig</th>
		<th>N50</th>
		<th style="text-align:center" width="30">Parameters</th>
		<th style="text-align:center" width="30">Curation</th>
		<th style="text-align:center" width="30">Status</th>
	</tr>
	<tr>
		<td>1</td>
		<td>48</td>
		<td>2.940.444</td>
		<td>500</td>
		<td>40.000</td>
		<td>20.300</td>
		<td style="text-align:center">
			<a style="color:#111;font-weight: bolder;" data-toggle="modal" data-target="#parameters1" href="#parameters1">
				<span class="glyphicon glyphicon-list-alt"></span>
			</a>
			<!-- Modal SOBRE -->
			<div class="modal fade" id="parameters1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
			    	<div class="modal-content">
			    		<div class="modal-header">
			        		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        		<h4 class="modal-title" id="myModalLabel">Parameters 1sttest</h4>
			      		</div>
			    		<div class="modal-body" style="text-align:left">
			    			<p>
				      			project = cp31<br/>
								job = genome,denovo,accurate<br/>
								parameters = -GE:not=16 IONTOR_SETTINGS -AS:mrpc=100<br/>
								<br/>
								readgroup = fragment<br/>
								technology = iontor<br/>
								data=../../data/*.fastq
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
			<a href="{{ URL::to('projects/cp31/trial/1') }}" style="color:#990000" title="Curation">
				<span class="glyphicon glyphicon-plus-sign"></span>
			</a>
		</td>
		<td style="text-align:center">
			<a href="#" title="OK" style="color:#009900">
				<span class="glyphicon glyphicon-ok"></span>
			</a>
		</td>
	</tr>
</table>
<div style="height:100px"></div>
<p style="text-align:center;background-color:#eee"><b>Note: </b>AMW uses <a target="_blank" href="//mira-assembler.sourceforge.net/docs/DefinitiveGuideToMIRA.html">Mira</a> to <i>de novo</i> assembly.</p>


@stop