@extends('layouts.master')

@section('conteudo')

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li><a href="{{ URL::to('projects') }}">Projects</a></li>
  <li><a href="{{ URL::to('projects') }}/{{ $project->id_project }}">{{ $project->name_project }}</a></li>  
  <li class="active">New assembly</li>
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
<form role="form" method="POST" action="../run_new_assembly">
	
	<div class="tabbable tabs-left">
		<ul class="nav nav-tabs">
			<li class=""><a href="#lA" data-toggle="tab">Advanced assembly</a></li>
			<li class="active"><a href="#lB" data-toggle="tab">Default assembly</a></li>
		</ul>
		
		<div class="tab-content">
			<div class="tab-pane" id="lA">
				<!-- Continuacao do formulario -->
				<div class="form-group">
					<input type="hidden" name="version" value="{{ $version }}" />
					<input type="hidden" name="name_project" value="{{ $project->name_project }}" />
									
					<br/><br/>
					<div class="alert alert-danger">
						<label class="label label-danger">ASSEMBLER:</label><br/><br/>
						<select name="assembler" class="form-control">
							<option value="mira">Mira 4.0.2</option>
							<option value="mira39" selected="selected">Mira 3.9</option>
							<option value="newbler">Newbler</option>
							<option value="minia">Minia</option>
							<option value="spades">SPAdes 3.6.0</option>
							<option value="text">Manual assembly</option>
						</select>
					</div>
									
					<br/>
									
					<div class="panel panel-info">
						<div class="panel-heading">
							<h3 class="panel-title">Mira options</h3>
						</div>
						<div class="panel-body">
							<br/>
									
							<label class="label label-info">JOB:</label><br/><br/>
							<div class="row">
								<div class="col-xs-6 col-md-4">
									<select name="job_1" class="form-control">
									    <option value="genome">genome</option>
									    <option value="est">est</option>
									</select>
								</div>
								<div class="col-xs-6 col-md-4">
									<select name="job_2" class="form-control">
										<option value="denovo">de novo</option>
									    <option value="mapping">mapping</option>
									</select>
								</div>
								<div class="col-xs-6 col-md-4">
									<select name="job_3" class="form-control">
									    <option value="accurate">accurate</option>
									    <option value="draft">draft</option>
									</select>
								</div>
							</div>
							<br/><br/>
							    	    	
							<label class="label label-info">NGS:</label><br/><br/>
							<select name="ngs_assembly" class="form-control">
								<option value="IONTOR">Ion Torrent (Proton/PGM)</option>
								<option value="SOLEXA">Illumina (Myseq/Hiseq)</option>
								<option value="PACBIO">Pacbio</option>
								<option value="454">454 Roche</option>
							</select>
							    
							<br/><hr/>
								 			
							<div class="form-group">
								<div class="row">
									<div class="col-xs-6 col-md-4">
										<label class="label label-info">Readgroup (dafault):</label>
									</div>
									<div class="col-xs-6 col-md-4">
										<label class="label label-info">Template size (optional):</label>
									</div>
									<div class="col-xs-6 col-md-4">
										<label class="label label-info">Segment place (optional):</label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6 col-md-4">
									<input type="text" name="readgroup" value="fragment" placeholder="Type a name for the readgroup" class="form-control" />
								</div>
								<div class="col-xs-6 col-md-4">
									<div class="row">
										<div class="col-xs-2 col-md-4">
								   			<input type="text" name="ts_1" class="form-control" />
								    	</div>
										<div class="col-xs-2 col-md-4">
								    		<input type="text" name="ts_2" class="form-control" />
								    	</div>
								    </div>
								</div>
								<div class="col-xs-6 col-md-4">
									<select name="segment" class="form-control">
								    	<option value="">none</option>
								    	<option value="---> --->">---> ---></option>
								    	<option value="---> <---">---> <---</option>    		
								    	<option value="<--- --->"><--- ---></option>
								    	<option value="<--- <---"><--- <---</option>
								    </select>
								</div>
							</div>
								    	
							<br/><br/>
												
							<input name="autopairing" type="checkbox"> Auto-pairing<br/><br/>
												
							<div class="row">
								<div class="col-xs-6 col-md-4">
									<label class="label label-info">General parameters extras (optional):</label><br/><br/>
									<textarea class="form-control" placeholder="E.g.: -AS:urd=yes" name="general_parameters" >-GE:not=16</textarea><br/>	
								</div>
								<div class="col-xs-6 col-md-4">
									<label class="label label-info">NGS parameters extras (optional):</label><br/><br/>
									<textarea class="form-control" placeholder="E.g.: :mrl=30:epoq=yes" name="ngs_parameters" >-AS:mrpc=100</textarea><br/>	
								</div> 
							</div>
						</div>
					</div>
				</div>
									
				<!-- Minia info -->
				<div class="panel panel-warning">
				   	<div class="panel-heading">
				        <h3 class="panel-title">Minia options</h3>
				    </div>
				    <div class="panel-body">
				    	<br/>
				    	<div class="col-xs-6 col-md-4">
					    	<label class="label label-warning">K-mer size</label>
					      	<input type="text" name="minia_kmer" placeholder="Max value: 31" class="form-control" />
					    </div>
					    <div class="col-xs-6 col-md-4">
					    	<label class="label label-warning">Length genome</label>
					      	<input type="text" name="minia_len_genome" placeholder="E.g.: 2500000" class="form-control" />
					    </div>
				      	<br/>
				   	</div>
				</div>
								      
				<!-- Newbler info -->
				<div class="panel panel-primary">
				   	<div class="panel-heading">
				       	<h3 class="panel-title">Newbler options</h3>
				   	</div>
				   	<div class="panel-body">
				 		<br/>
				 		<div class="col-xs-6 col-md-4">
							<label class="label label-primary">Number of processors</label>
							<input type="text" name="newbler_processors" placeholder="E.g.: 16" class="form-control" />
							<br/><br/>
							<label class="label label-primary">% for SubSample</label>
							<input type="text" name="newbler_cluster" placeholder="E.g.: 100 (Default is 100%, full raw data file)" class="form-control" />
							<br/><br/>
							<label class="label label-primary">Cut (barcode)</label>
							<input type="text" name="newbler_cut" placeholder="E.g.: 18 (Default is 0, full read)" class="form-control" />
						
						</div>	
						<br/>
					</div>
				</div>

				<!-- Spades info -->
				<div class="panel panel-primary">
				   	<div class="panel-heading">
				       	<h3 class="panel-title">SPAdes options</h3>
				   	</div>
				   	<div class="panel-body">
				 		<br/>
				 		<div class="col-xs-6 col-md-4">
							<label class="label label-primary">Number of processors</label>
							<input type="text" name="spades_processors" placeholder="E.g.: 16" class="form-control" />
							<br/><br/>
							<label class="label label-primary">% for SubSample</label>
							<input type="text" name="spades_cluster" placeholder="E.g.: 100 (Default is 100%, full raw data file)" class="form-control" />
							<br/><br/>
							<label class="label label-primary">Cut (barcode)</label>
							<input type="text" name="spades_cut" placeholder="E.g.: 18 (Default is 0, full read)" class="form-control" />
						
						</div>	
						<br/>
					</div>
				</div>
				
				<br/>
				
				<input type="submit" name="submit" class="btn btn-success" value="Run assembly" />
										
				<br/>
			</div>
		
			<div class="tab-pane active" id="lB">
				<!-- Formulario default -->
				<br/>
				<b>Assembler: </b>Mira 3.9<br/>
				<b>NGS: </b>Ion Torrent<br/>
				<b>Library: </b>fragment<br/>
				<b>Job: </b>denovo, genome, accurate<br/>
				<b>General parameters: </b>not=16<br/> 
				<b>NGS parameters: </b>mrpc=100
				<br/><br/>
				<input type="submit" name="submit" class="btn btn-success" value="Click to run assembly with default parameters" />
				<!-- End -->
			</div>
		</div>
	</div>
</form>
<!-- End -->
	

<div style="height:50px"></div>
<div class="alert alert-info" style="text-align:center">
	<b>Note: </b>SIMBA uses <a target="_blank" href="//mira-assembler.sourceforge.net/docs/DefinitiveGuideToMIRA.html">Mira</a> to <i>de novo</i> assembly and <a target="_blank" href="https://github.com/dcbmariano/scripts">CONTIGinfo</a> to analyze information about results of assemblies.
</div>

@stop