@extends('layouts.master')

@section('conteudo')

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li><a href="{{ URL::to('projects') }}">Projects</a></li>
  <li><a href="{{ URL::to('projects') }}/{{ $project->id_project }}/assemblies">{{ $project->name_project }}</a></li>
  <li class="active">Trial {{ $assembly->version_assembly }}</li>
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

<br/>

<table class="table table-condensed table-striped table-bordered">
	<tr>
		<th style="text-align:center" width="20">Step</th>
		<th style="text-align:center" width="20">Status</th>
		<th>Action</th>
		<th style="text-align:center">Gaps</th>
		<th style="text-align:center" width="120">Synteny chart</th>
		<th style="text-align:center" colspan="2" width="30">Download</th>
		<th style="text-align:center" width="20">Action</th>
		
	</tr>
	<!-- Curation - STEP 1 -->
	<tr>
		<td style="text-align:center" ><b>1</b></td>
		<td style="text-align:center">
			<?php if(isset($f1->version_curation)){ ?>
				<span title="OK" style="color:#009900" class="glyphicon glyphicon-ok"></span>
			<?php } else { ?>
				<span style="color:#990000" class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td>Set reference</td>
		<td style="text-align:center">
			<?php if(isset($f1->version_curation)){ ?>
			
				{{ $f1->num_scaffolds-1 }}	
				
			<?php } else { ?>
				<span class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<?php if(isset($f1->version_curation)){ ?>
			<a data-toggle="modal" data-target="#f1" href="#f1">
				<span class="glyphicon glyphicon-picture"></span>
			</a>
			
			<!-- Modal f1 -->            
			<div class="modal fade" id="f1" style="width:100%; " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  >
				<div class="modal-dialog" style="width: 90%">
			    	<div class="modal-content">
			    		<div class="modal-header">
			        		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        		<h4 class="modal-title" id="myModalLabel"><b>F1</b></h4>
			      		</div>
			    		<div class="modal-body" style="text-align:center">
			    			<img src="{{ URL::to('/') }}/tmp/f1_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.png" width="100%"/>
			      		</div>
			      		<div class="modal-footer">
			      			<a class="btn btn-default" target="_blank" href="{{ URL::to('/') }}/tmp/f1_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.pdf">Full image (PDF)</a>
			        		<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			      		</div>
			    	</div><!-- /.modal-content -->
			  	</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			<!-- End -->
			<?php } else { ?>
				<span class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<?php if(isset($f1->version_curation)){ ?>
			<a target="_blank" href="{{ URL::to('/') }}/tmp/f1_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.fasta" title="SCAFFOLDS">
				<span class="glyphicon glyphicon-stop"></span>
			</a>
			<?php } else { ?>
				<span class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<?php if(isset($f1->version_curation)){ ?>
			<a target="_blank" href="{{ URL::to('/') }}/tmp/m1_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.fasta" title="CONTIGS">
				<span class="glyphicon glyphicon-align-justify"></span>
			</a>
			<?php } else { ?>
				<span class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<!-- Split button -->
			<div class="btn-group">
			  <button type="button" class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
			    <span class="caret"></span>
			    <span class="sr-only">Action</span>
			  </button>
				<ul style="text-align:left" class="dropdown-menu pull-right" role="menu"> 
						<li>
							<a href="{{ URL::to('projects') }}/{{ $project->id_project }}/assemblies/{{ $assembly->id_assembly }}/F1" title="RUN STEP 1">
								RUN STEP 1
							</a>
						</li>
				</ul>
			</div>
		</td>
		
	</tr>	
	<!-- Curation - STEP 2 -->
	<tr>
		<td style="text-align:center"><b>2</b></td>
		<td style="text-align:center">
			<?php if(isset($f2->version_curation)){ ?>
				<span style="color:#009900" class="glyphicon glyphicon-ok"></span>
			<?php } else { ?>
				<span style="color:#990000" class="glyphicon glyphicon-remove"></span>
			<?php } ?>		
		</td>
		<td>Move dnaA</td>
		<td style="text-align:center">
			<?php if(isset($f2->version_curation)){ ?>
				{{ $f1->num_scaffolds-1 }}	
			<?php } else { ?>
				<span class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<?php if(isset($f2->version_curation)){ ?>
				<a data-toggle="modal" data-target="#f2" href="#f2">
				<span class="glyphicon glyphicon-picture"></span>
			</a>
			
			<!-- Modal f2 -->
            
			<div class="modal fade" id="f2" style="width:100%; " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  >
				<div class="modal-dialog" style="width: 90%">
			    	<div class="modal-content">
			    		<div class="modal-header">
			        		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        		<h4 class="modal-title" id="myModalLabel"><b>F2</b></h4>
			      		</div>
			    		<div class="modal-body" style="text-align:center">
			    			<img src="{{ URL::to('/') }}/tmp/f2_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.png" width="100%"/>
			      		</div>
			      		<div class="modal-footer">
			      			<a class="btn btn-default" target="_blank" href="{{ URL::to('/') }}/tmp/f2_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.pdf">Full image (PDF)</a>
			        		<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			      		</div>
			    	</div><!-- /.modal-content -->
			  	</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			<!-- End -->
			<?php } else { ?>
				<span class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<?php if(isset($f2->version_curation)){ ?>
				<a target="_blank" href="{{ URL::to('/') }}/tmp/f2_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.fasta" title="SCAFFOLDS">
					<span class="glyphicon glyphicon-stop"></span>
				</a>
			<?php } else { ?>
				<span class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<?php if(isset($f2->version_curation)){ ?>
				<a target="_blank" href="{{ URL::to('/') }}/tmp/m2_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.fasta" title="CONTIGS">
					<span class="glyphicon glyphicon-align-justify"></span>
				</a>
			<?php } else { ?>
				<span class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<!-- Split button -->
			<div class="btn-group">
			  <button type="button" class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
			    <span class="caret"></span>
			    <span class="sr-only">Action</span>
			  </button>
				<ul style="text-align:left" class="dropdown-menu pull-right" role="menu"> 
					<?php if(isset($f1->version_curation)){ ?>
						<li>
							<a href="{{ URL::to('projects') }}/{{ $project->id_project }}/assemblies/{{ $assembly->id_assembly }}/F2" title="RUN STEP 2">
								RUN STEP 2
							</a>
						</li>
					<?php } else { ?>
						<li class="disabled">	
				    		<a href="#" class="disabled">No action available</a>
				    	</li>
					<?php } ?>
				</ul>
			</div>
		</td>
	</tr>
	<!-- Curation - STEP 3 -->
	<tr>
		<td style="text-align:center"><b>3</b></td>
		<td style="text-align:center">
			<?php if(isset($f3->version_curation)){ ?>
				<span style="color:#009900" class="glyphicon glyphicon-ok"></span>
			<?php } else { ?>
				<span style="color:#990000" class="glyphicon glyphicon-remove"></span>
			<?php } ?>	
		</td>
		<td>Building Supercontigs</td>
		<td style="text-align:center">
			<?php if(isset($f3->version_curation)){ ?>
				{{ $f3->num_scaffolds-1 }}	
			<?php } else { ?>
				<span class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<?php if(isset($f3->version_curation)){ ?>
				<a data-toggle="modal" data-target="#f3" href="#f3">
				<span class="glyphicon glyphicon-picture"></span>
			</a>
			
			<!-- Modal f3 -->
            
			<div class="modal fade" id="f3" style="width:100%; " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  >
				<div class="modal-dialog" style="width: 90%">
			    	<div class="modal-content">
			    		<div class="modal-header">
			        		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			        		<h4 class="modal-title" id="myModalLabel"><b>F3</b></h4>
			      		</div>
			    		<div class="modal-body" style="text-align:center">
			    			<img src="{{ URL::to('/') }}/tmp/f3_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.png" width="100%"/>
			      		</div>
			      		<div class="modal-footer">
			      			<a class="btn btn-default" target="_blank" href="{{ URL::to('/') }}/tmp/f3_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.pdf">Full image (PDF)</a>
			        		<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			      		</div>
			    	</div><!-- /.modal-content -->
			  	</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			<!-- End -->
			<?php } else { ?>
				<span class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<?php if(isset($f3->version_curation)){ ?>
				<a target="_blank" href="{{ URL::to('/') }}/tmp/f3_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.fasta" title="SCAFFOLDS">
					<span class="glyphicon glyphicon-stop"></span>
				</a>
			<?php } else { ?>
				<span class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<?php if(isset($f3->version_curation)){ ?>
				<a target="_blank" href="{{ URL::to('/') }}/tmp/m3_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.fasta" title="CONTIGS">
					<span class="glyphicon glyphicon-align-justify"></span>
				</a>
			<?php } else { ?>
				<span class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<!-- Split button -->
			<div class="btn-group">
			  <button type="button" class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
			    <span class="caret"></span>
			    <span class="sr-only">Action</span>
			  </button>
				<ul style="text-align:left" class="dropdown-menu pull-right" role="menu"> 
					<?php if(isset($f2->version_curation)){ ?>
						<li>
							<a href="{{ URL::to('projects') }}/{{ $project->id_project }}/assemblies/{{ $assembly->id_assembly }}/F3" title="RUN STEP 3">
								RUN STEP 3
							</a>
						</li>
					<?php } else { ?>
						<li class="disabled">	
				    		<a href="#" class="disabled">No action available</a>
				    	</li>
					<?php } ?>
				</ul>
			</div>
		</td>
	</tr>
	<!-- Curation - STEP 4 -->
	<tr>
		<td style="text-align:center"><b>4</b></td>
		<td style="text-align:center">
			<?php if(isset($f4->version_curation)){ ?>
				<span style="color:#009900" class="glyphicon glyphicon-ok"></span>
			<?php } else { ?>
				<span style="color:#990000" class="glyphicon glyphicon-remove"></span>
			<?php } ?>		
		</td>
		<td>Analyze repetitive regions</td>
		<td style="text-align:center">
			<?php if(isset($f4->version_curation)){ ?>
				{{ $f4->num_scaffolds-1 }}	
			<?php } else { ?>
				<span class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<?php if(isset($f4->version_curation)){ ?>
				<a data-toggle="modal" data-target="#f4" href="#f4">
					<span class="glyphicon glyphicon-picture"></span>
				</a>
			
				<!-- Modal f4 -->
	            
				<div class="modal fade" id="f4" style="width:100%; " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  >
					<div class="modal-dialog" style="width: 90%">
				    	<div class="modal-content">
				    		<div class="modal-header">
				        		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				        		<h4 class="modal-title" id="myModalLabel"><b>F4</b></h4>
				      		</div>
				    		<div class="modal-body" style="text-align:center">
				    			<img src="{{ URL::to('/') }}/tmp/f4_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.png" width="100%"/>
				      		</div>
				      		<div class="modal-footer">
				      			<a class="btn btn-default" target="_blank" href="{{ URL::to('/') }}/tmp/f4_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.pdf">Full image (PDF)</a>
				        		<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				      		</div>
				    	</div><!-- /.modal-content -->
				  	</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
				<!-- End -->
			<?php } else { ?>
				<span class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<?php if(isset($f4->version_curation)){ ?>
				<a target="_blank" href="{{ URL::to('/') }}/tmp/f4_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.fasta" title="SCAFFOLDS">
					<span class="glyphicon glyphicon-stop"></span>
				</a>
			<?php } else { ?>
				<span class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<?php if(isset($f4->version_curation)){ ?>
				<a target="_blank" href="{{ URL::to('/') }}/tmp/m4_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.fasta" title="CONTIGS">
					<span class="glyphicon glyphicon-align-justify"></span>
				</a>
			<?php } else { ?>
				<span class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<!-- Split button -->
			<div class="btn-group">
			  <button type="button" class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
			    <span class="caret"></span>
			    <span class="sr-only">Action</span>
			  </button>
				<ul style="text-align:left" class="dropdown-menu pull-right" role="menu"> 
					<?php if(isset($f3->version_curation)){ ?>
						<li>
							<a href="{{ URL::to('projects') }}/{{ $project->id_project }}/assemblies/{{ $assembly->id_assembly }}/F4" title="RUN STEP 4">
								RUN STEP 4
							</a>
						</li>
					<?php } else { ?>
						<li class="disabled">	
				    		<a href="#" class="disabled">No action available</a>
				    	</li>
					<?php } ?>
				</ul>
			</div>
		</td>
	</tr>
	
	<!-- Curation - STEP 5 -->
	<tr>
		<td style="text-align:center"><b>5</b></td>
		<td style="text-align:center">
			<?php if(isset($f5->version_curation)){ ?>
				<span style="color:#009900" class="glyphicon glyphicon-ok"></span>
			<?php } else { ?>
				<span style="color:#990000" class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td>Statistics and manual curation</td>
		<td style="text-align:center">
			<?php if(isset($f5->version_curation)){ ?>
				{{ $f5->num_scaffolds-1 }}	
			<?php } else { ?>
				<span class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<?php if(isset($f5->version_curation)){ ?>
				<a data-toggle="modal" data-target="#f5" href="#f5">
					<span class="glyphicon glyphicon-picture"></span>
				</a>
			
				<!-- Modal f5 -->
	            
				<div class="modal fade" id="f5" style="width:100%; " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  >
					<div class="modal-dialog" style="width: 90%">
				    	<div class="modal-content">
				    		<div class="modal-header">
				        		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				        		<h4 class="modal-title" id="myModalLabel"><b>F5</b></h4>
				      		</div>
				    		<div class="modal-body" style="text-align:center">
				    			<img src="{{ URL::to('/') }}/tmp/f5_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.png" width="100%"/>
				      		</div>
				      		<div class="modal-footer">
				      			<a class="btn btn-default" target="_blank" href="{{ URL::to('/') }}/tmp/f5_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.pdf">Full image (PDF)</a>
				        		<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				      		</div>
				    	</div><!-- /.modal-content -->
				  	</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
				<!-- End -->
			<?php } else { ?>
				<span class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<?php if(isset($f5->version_curation)){ ?>
				<a target="_blank" href="{{ URL::to('/') }}/tmp/f5_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.fasta" title="SCAFFOLDS">
					<span class="glyphicon glyphicon-stop"></span>
				</a>
			<?php } else { ?>
				<span class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<?php if(isset($f5->version_curation)){ ?>
				<a target="_blank" href="{{ URL::to('/') }}/tmp/m5_a{{ $assembly->id_assembly }}_{{ $project->name_project }}.fasta" title="CONTIGS">
					<span class="glyphicon glyphicon-align-justify"></span>
				</a>
			<?php } else { ?>
				<span class="glyphicon glyphicon-remove"></span>
			<?php } ?>
		</td>
		<td style="text-align:center">
			<!-- Split button -->
			<div class="btn-group">
			  <button type="button" class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
			    <span class="caret"></span>
			    <span class="sr-only">Action</span>
			  </button>
				<ul style="text-align:left" class="dropdown-menu pull-right" role="menu"> 
					<?php if(isset($f4->version_curation)){ ?>
						<li>
							<a href="{{ URL::to('projects') }}/{{ $project->id_project }}/assemblies/{{ $assembly->id_assembly }}/F5" title="RUN STEP 5">
								RUN STEP 5
							</a>
						</li>
					<?php } else { ?>
						<li class="disabled">	
				    		<a href="#" class="disabled">No action available</a>
				    	</li>
					<?php } ?>
				</ul>
			</div>
		</td>
	</tr>
</table>

<div style="float:right;text-align:right;font-size:12px;font-family:arial">
	<p>
	<b>Organism: </b> <i>{{ $project->organism_project }}</i><br/>
	<b>Date: </b> {{ $project->created_at }}
	<br/><br/></p>
</div>

<div style="clear:both"></div>

<br/>

<div class="alert alert-info" style="text-align:center">
	<b>Note: </b>SIMBA uses <a target="_blank" href="#">CONTIGuator</a> to generate <i>scaffolds</i>.</p>
</div>

@stop