@extends('layouts.master')
@section('conteudo')
<!-- Insira o conteudo da pagina aqui -->

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li><a href="{{ URL::to('tools') }}">Tools</a></li>
  <li class="active">Web CONTIGuator</li>
</ol>
<!-- Fim navegacao -->

<h1>Web CONTIGuator</h1>

<?php echo Form::open(array('url' => 'tools/run_webcontiguator', 'files' => true)); ?>
	
	<br/>
	<table class="table table-striped table-bordered table-hover">
		<tr>
			<td><label class="label label-success">Contigs: </label></td>
			<td><input type="file" name="contigs" /></td>
		</tr>
		<tr>
			<td><label class="label label-success">Reference: </label></td>
			<td><input type="file" name="reference" /></td>
		</tr>
	</table>
	<p><br/><br/></p>
	<input type="submit" name="submit" class="btn btn-success col-xs-12" value="Run Web Contiguator" /> 
	<br/><br/><br/>
	
</form>

@stop