@extends('layouts.master')

@section('conteudo')

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li class="active">Projects</li>
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

<script src="{{ URL::to('/') }}/js/jquery.js"></script>

<script type="text/javascript">
$(function(){
    $("#tabela input").keyup(function(){        
        var index = $(this).parent().index();
        var nth = "#tabela td:nth-child("+(index+1).toString()+")";
        var valor = $(this).val().toUpperCase();
        $("#tabela tr").show();
        $(nth).each(function(){
            if($(this).text().toUpperCase().indexOf(valor) < 0){
                $(this).parent().hide();
            }
        });
    });
 
    $("#tabela input").blur(function(){
        $(this).val("");
    }); 
});
</script>

<p style="text-align:right">
	<a href="#" data-toggle="modal" data-target="#new_project" class="btn btn-success">New project</a>
</p>
		
<table id="tabela" class="table table-responsive table-condensed table-striped table-bordered">
	<tr>
		<th>Status</th>
		<th>Name <input style="margin-left:5px;width:100px" type="text" placeholder="Filter" id="txtColuna2" /></th>
		<th>Organism <input style="margin-left:5px;width:100px" type="text" placeholder="Filter" id="txtColuna2" /></th>
		<th>NGS</th>
		<th>Library</th>
		<th style="text-align:center">BAM</th>
		<th style="text-align:center">SFF</th>
		<th style="text-align:center">FASTQ</th>
		<th style="text-align:center">Assembly</th>
		<th style="text-align:center">Action</th>	
	</tr>
	
	<!-- Loop banco de dados -->
	@foreach($projects as $p)
	
	<?php 
		/* Calculando porcentagem concluida - status */
		$status_project = 0;
		$gaps = 999;
		foreach($curations as $c){						
			$gaps = $c->num_scaffolds - 1;
			if($p->fastq == 1)
				$status_project = 15;			
			if($p->assembly == 1)
				$status_project = 30;
			if($c->fk_id_project == $p->id_project){
				switch($c->version_curation){
					case 1: $status_project = 44; break;
					case 2: $status_project = 58; break;
					case 3: $status_project = 72; break;
					case 4: $status_project = 86; break;
					case 5: $status_project = 100; break;
					default: $status_project = 0; break;   
				}	
				break; // reduz o processamento
			}
		}
		/* Definindo cor */
		if($status_project < 31) $status_color = 'danger';
		if($status_project > 30 and $status_project < 72) $status_color = 'warning';
		if($status_project > 71) $status_color = 'success';
	?>
	<tr>
		<td width="120px">
			<div class="progress" style="margin-bottom:0px">
  				<div class="progress-bar progress-bar-{{ $status_color }}" role="progressbar" aria-valuenow="{{ $status_project }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $status_project }}%;" title="{{ $gaps }} gaps"></div>
			</div>
		</td>
		<td><a href="<?php if ($p->fastq != 0) echo URL::to('projects').'/'.$p->id_project.'/assemblies'; else echo '#'; ?>">{{ $p->name_project }}</a></li></td>
		<td style="font-style:italic">{{ $p->organism_project }}</td>
		<td>{{ $p->ngs_project }}</td>
		<td>{{ $p->library_project }}</td>
		<td style="text-align:center">
			@if ($p->bam == 0)
				<span style="color:#990000" class="glyphicon glyphicon-remove"></span>
			@endif
			@if ($p->bam == 1)
				<span style="color:#009900" class="glyphicon glyphicon-ok"></span>
			@endif
		</td>
		<td style="text-align:center">
			@if ($p->sff == 0)
				<span style="color:#990000" class="glyphicon glyphicon-remove"></span>
			@endif
			@if ($p->sff == 1)
				<span style="color:#009900" class="glyphicon glyphicon-ok"></span>
			@endif
		</td>		
		<td style="text-align:center">
			@if ($p->fastq == 0)
				<span style="color:#990000" class="glyphicon glyphicon-remove"></span>
			@endif
			@if ($p->fastq == 1)
				<span style="color:#009900" class="glyphicon glyphicon-ok"></span>
			@endif
		</td>	
		<td style="text-align:center">
			@if ($p->assembly == 0)
				<span style="color:#990000" class="glyphicon glyphicon-remove"></span>
			@endif
			@if ($p->assembly == 1)
				<span style="color:#009900" class="glyphicon glyphicon-ok"></span>
			@endif
		</td>	
		<td style="text-align:center">
			<!-- Split button -->
			<div class="btn-group">
			  <button type="button" class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
			    <span class="caret"></span>
			    <span class="sr-only">Action</span>
			  </button>
			  
			  <ul style="text-align:left" class="dropdown-menu pull-right" role="menu"> 
			  	<li><a href="{{ URL::to('projects/') }}/{{ $p->id_project }}/edit">Edit project</a></li>
			  	<li><a href="{{ URL::to('projects/') }}/{{ $p->id_project }}/delete">Delete project</a></li>
			  	
			    <li <?php if ($p->bam == 0) echo 'class="disabled"'; ?>><a href="<?php if ($p->bam != 0) echo URL::to('action').'/bam2sff/'.$p->name_project; else echo '#'; ?>">Generate SFF file</a></li>
			    <li <?php if ($p->sff == 0) echo 'class="disabled"'; ?>><a href="<?php if ($p->sff != 0) echo URL::to('action').'/sff_extract/'.$p->name_project; else echo '#'; ?>">Extract FASTQ file</a></li>
			    
			    <li <?php if ($p->fastq == 0) echo 'class="disabled"'; ?>><a href="<?php if ($p->fastq != 0) echo URL::to('action').'/fastqc/'.$p->name_project; else echo '#'; ?>">FastQC Report</a></li>
			    
			    <li <?php if ($p->fastq == 0) echo 'class="disabled"'; ?>>
				  	<a target="_blank" href="../app/assembly/{{ $p->name_project }}">
						Download raw data
					</a>
				</li>
			    
			    <li <?php if ($p->fastq == 0) echo 'class="disabled"'; ?>><a href="<?php if ($p->fastq != 0) echo URL::to('projects').'/'.$p->id_project.'/assemblies'; else echo '#'; ?>">New assembly</a></li>
			  	
			  </ul>
			</div>
		</td>
	</tr>
	@endforeach
	<!-- End loop -->
</table>


<!-- Modal NEW PROJECT -->
<div class="modal fade" id="new_project" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			    <h4 class="modal-title" id="myModalLabel">Warning!</h4>
			</div>
			<div class="modal-body" style="text-align:left">
			    <p>To create a new project, first, create a folder in [www_folder_default]/simba/app/assembly/[project_name]. And put the BAM file in this folder. After, click "Update projects list".</p>
			    <label class="label label-danger">Warning: If you want make a test, you can force a creation of a new project, clicking in "New project".</label>
			</div>
			<div class="modal-footer">
				<a href="{{ URL::to('projects/create') }}" class="btn btn-success">New project</a>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End -->

<br/><br/>

<div style="text-align: center">
	<a style="font-size:30px;text-align: center" href="{{URL::to('action/update_projects')}}">
		<span class="glyphicon glyphicon-refresh"></span>
		<span class="glyphicon-class">UPDATE</span>
	</a>
</div>
	
<br/>

@stop