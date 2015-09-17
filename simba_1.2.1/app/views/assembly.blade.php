@extends('layouts.master')

@section('conteudo')

<?php $version_assembly = 1; ?>
@foreach($assemblies as $a)
<?php $last = $a->version_assembly; $version_assembly = $last + 1; ?>
@endforeach

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li><a href="{{ URL::to('projects') }}">Projects</a></li>
  <li class="active">{{ $project->name_project }}</li>
</ol>
<!-- Fim navegacao -->

<!-- Setor de erros -->
@if ( count($errors) > 0)
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

<p style="text-align:right">
@if ( count($assemblies)>0 )
<a href="{{ URL::to('projects') }}/{{ $project->id_project }}/new_quast/1" class="btn btn-success">Run QUAST</a>
@endif
<a href="{{ URL::to('projects') }}/{{ $project->id_project }}/new_assembly/{{ $version_assembly }}" class="btn btn-success">New assembly</a></p>
 
<table class="table table-responsive table-condensed table-striped table-bordered">
	<tr>
		<th style="text-align:center">Trial</th>
		<th style="text-align:center">Number contigs</th>
		<th style="text-align:center">Length genome (pb)</th>
		<th style="text-align:center">Min contig</th>
		<th style="text-align:center">Max contig</th>
		<th style="text-align:center">N50</th>		
		<th style="text-align:center">Date</th>
		<th style="text-align:center" >Assembly Info</th>
		<th style="text-align:center" width="30">Parameters</th>
		<th style="text-align:center" width="30">Action</th>
	</tr>
	@foreach($assemblies as $a)
	<tr>
		<td style="text-align:center"><b>{{ $a->version_assembly }}</b></a>
		</td>
		<td style="text-align:center">
			<?php $a->num_contigs_assembly = (int)$a->num_contigs_assembly; if($a->num_contigs_assembly == 0) echo '<span title="Trial is running or fail. Click UPDATE to check for new information about this trial." class="glyphicon glyphicon-remove"></span>'; else echo number_format($a->num_contigs_assembly,0,'','.'); ?>
		</td>
		<td style="text-align:center">
			<?php $a->len_genome_assembly = (int)$a->len_genome_assembly; if($a->len_genome_assembly == 0) echo '<span title="Trial is running or fail. Click UPDATE to check for new information about this trial." class="glyphicon glyphicon-remove"></span>'; else echo number_format($a->len_genome_assembly,0,'','.'); ?>
		</td>
		<td style="text-align:center">
			<?php $a->min_contig_assembly = (int)$a->min_contig_assembly; if($a->min_contig_assembly == 0) echo '<span title="Trial is running or fail. Click UPDATE to check for new information about this trial." class="glyphicon glyphicon-remove"></span>'; else echo number_format($a->min_contig_assembly,0,'','.'); ?>
		</td>
		<td style="text-align:center">
			<?php $a->max_contig_assembly = (int)$a->max_contig_assembly; if($a->max_contig_assembly == 0) echo '<span title="Trial is running or fail. Click UPDATE to check for new information about this trial." class="glyphicon glyphicon-remove"></span>'; else echo number_format($a->max_contig_assembly,0,'','.'); ?>
		</td>
		<td style="text-align:center">
			<?php $a->n50_assembly = (int)$a->n50_assembly; if($a->n50_assembly == 0) echo '<span title="Trial is running or fail. Click UPDATE to check for new information about this trial." class="glyphicon glyphicon-remove"></span>'; else echo number_format($a->n50_assembly,0,'','.'); ?>
		</td>
		<td style="text-align:center">
			<?php if($a->n50_assembly == 0) echo '<span title="Trial is running or fail. Click UPDATE to check for new information about this trial." class="glyphicon glyphicon-remove"></span>'; else echo $a->created_at; ?>
		</td>
		<td style="text-align:center">
			<a style="color:#111;font-weight: bolder;" data-toggle="modal" data-target="#info_{{ $a->id_assembly }}" href="#info_{{ $a->id_assembly }}">
				<span class="glyphicon glyphicon-list-alt"></span>
			</a>
			<!-- Modal INFO -->
			<div class="modal fade" style="width:100%; " id="info_{{ $a->id_assembly }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog" style="width: 70%">
			    	<div class="modal-content">
			    		<div class="modal-header">
			        		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        		<h4 class="modal-title" id="myModalLabel"><b>Assembly Info | Trial: {{ $a->version_assembly }}</b></h4>
			      		</div>
			    		<div class="modal-body" style="text-align:left">
			    			<p>
				      			<pre><?php if($a->info_assembly == '') print 'No info available.'; else print $a->info_assembly; ?></pre>
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
			<a style="color:#111;font-weight: bolder;" data-toggle="modal" data-target="#parameters_{{ $a->id_assembly }}" href="#parameters_{{ $a->id_assembly }}">
				<span class="glyphicon glyphicon-list-alt"></span>
			</a>
			<!-- Modal PARAMETERS -->
			<div class="modal fade" id="parameters_{{ $a->id_assembly }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
			    	<div class="modal-content">
			    		<div class="modal-header">
			        		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        		<h4 class="modal-title" id="myModalLabel"><b>Parameters | trial: {{ $a->version_assembly }}</b></h4>
			      		</div>
			    		<div class="modal-body" style="text-align:left">
			    			<pre>{{ $a->parameters_assembly }}</pre>
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
			<!-- Split button -->
			<div class="btn-group">
			  <button type="button" class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
			    <span class="caret"></span>
			    <span class="sr-only">Action</span>
			  </button>
			  
			  <ul style="text-align:left" class="dropdown-menu pull-right" role="menu"> 			  	
			    <?php if($a->num_contigs_assembly == 0){ ?>
			    	<li>
				  		<a href="{{ URL::to('projects') }}/{{ $a->fk_id_project }}/delete_assembly/{{ $a->id_assembly }}">Delete assembly</a>
				  	</li>
			    	<li class="disabled">	
			    		<a href="#" class="disabled">No more action available</a>
			    	</li>
			    <?php } else { ?>	
			    	<li>			    	
			    		<a href="{{ URL::to('projects')}}/{{ $a->fk_id_project }}/assemblies/{{ $a->id_assembly }}" title="Curation">
							Curation
						</a>
						<a href="../../../app/assembly/{{ $project->name_project }}/t{{ $a->version_assembly }}_assembly/t{{ $a->version_assembly }}_d_results/t{{ $a->version_assembly }}_out.unpadded.fasta">
							Download contigs
						</a>
					</li>
				<?php } ?>    
			  </ul>
			</div>
		</td>
		
	</tr>
	@endforeach
</table>

<div style="font-size:12px;font-family:arial">
	<p>
	<b>Organism: </b> <i>{{ $project->organism_project }}</i><br/>
	<b>Project name: </b> {{$project->name_project }} <br/>
	<b>Created at: </b> {{ $project->created_at }}
	<br/><br/></p>
</div>

<div style="text-align: center">
	<a style="font-size:30px;text-align: center" href="{{ URL::to('action/update_assemblies_info') }}/{{ $project->id_project }}">
		<span class="glyphicon glyphicon-refresh"></span>
		<span class="glyphicon-class">UPDATE</span>
	</a>
</div>

<p><br/></p>

<div class="alert alert-info" style="text-align:center">
	<b>Note: </b>SIMBA uses <a target="_blank" href="//mira-assembler.sourceforge.net/docs/DefinitiveGuideToMIRA.html">Mira</a> to <i>de novo</i> assembly and <a target="_blank" href="https://github.com/dcbmariano/scripts">CONTIGinfo</a> to analyze information about results of assemblies.
</div>


@stop