@extends('layouts.master')
@section('conteudo')
<!-- Insira o conteudo da pagina aqui -->

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li class="active">Tools</li>
</ol>
<!-- Fim navegacao -->

<h2><b>Tools</b></h2>
<br/>

<table class="table table-condensed table-striped table-bordered">
	<tr>
		<th>Tool</th>
		<th>Description</th>
		<th>Version</th>
		<th>Action</th>
	</tr>
	<tr>
		<th>Web CONTIGuator</th>
		<td>Allows to make alignment between contigs and reference genome.</td>
		<td>Final</td>
		<td><a href="{{ URL::to('/') }}/tools/webcontiguator">Run</a></td>
	</tr>
	<!--
	<tr>
		<th>Supercontigs constructor</th>
		<td>Allows you to link two neighboring contigs by aligning ends.</td>
		<td>Beta</td>
		<td><a href="{{ URL::to('/') }}/tools/supercontigs">Run</a></td>
	</tr>
	<tr>
		<th>LegoScaffold</th>
		<td>Simple interface for ordering of contigs.</td>
		<td>Alpha</td>
		<td><a href="{{ URL::to('/') }}/tools/legoscaffold">Run</a></td>
	</tr>
	<tr>
		<th>scaffoldHibrido</th>
		<td>Compara duas diferentes montagens.</td>
		<td>Alpha</td>
		<td><a href="{{ URL::to('/') }}/tools/scaffoldhibrido">Run</a></td>
	</tr>
	-->
</table>
<!-- End -->
@stop