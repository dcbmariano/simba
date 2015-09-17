@extends('layouts.master')

@section('conteudo')

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
<script>
function filtro(){
	var e=event.keyCode;
	
	if (event.shiftKey == false){
		if (e==8 || e==32 || e==35 || e==128 || e==32 || (e>=225 && e<=252) || e==36 || e==46 || (e>=48 && e<=57) || (e>=65 && e<=90) || (e>=96 && e<=105)){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}
</script>

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li><a href="{{ URL::to('projects') }}">Projects</a></li>
  <li class="active">New project</li>
</ol>
<!-- Fim navegacao -->

<?php echo Form::open(array('url' => 'projects/store', 'files' => true)); ?>
	<div class="form-group">
    	<label>Folder:</label>
    	<input type="text" name="name_project" class="form-control" onKeyDown="return filtro()" placeholder="Name Organism (E.g.: coryne_pseud_1002. Don't use CAPS LOCK or type spaces)">
	</div>
	<div class="form-group">
    	<label>Name organism:</label>
    	<input type="text" name="organism_project" class="form-control" placeholder="Specie organism strain">
	</div>
	<div class="form-group">
    	<label>NGS:</label><br/>
    	<select name="ngs_project" class="form-control">
    		<option value="iontor">iontor</option>
    		<option value="illumina">illumina</option>
    		<option value="pacbio">pacbio</option>
    		<option value="454">454</option>
    	</select>
    </div>
	<div class="form-group">
    	<label>Library</label>
    	<input type="text" name="library_project" class="form-control" placeholder="Library (E.g.: fragment, mate-pair (2kb, 3kb, 10kb, 20kb), paired-end)">
	</div>
	<input type="hidden" name="bam" value="0" />
	<input type="hidden" name="sff" value="0" />
	<input type="hidden" name="fastq" value="0" />
	<input type="hidden" name="assembly" value="0" />
	<label>Raw data</label>
	<input type="file" name="raw_data" /><br/><br/>
	<input type="submit" name="submit" class="btn btn-success col-xs-12" value="Create" />
</form>

<br/><br/>
@stop