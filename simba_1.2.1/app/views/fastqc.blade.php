@extends('layouts.master')
@section('conteudo')

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li><a href="{{ URL::to('projects') }}">Projects</a></li>
  <li class="active">FastQC report</li>
</ol>
<!-- Fim navegacao -->

<h2><b>FastQC Report:</b> {{ $project->name_project }}</h2>
<p><br/></p>

<?php $url = URL::to('/').'/tmp/fastqc/'.$project->name_project.'/'.$project->name_project.'_fastqc/'; ?>

<table class="table table-striped table-bordered">
	<tr>
		<td><h3>Per base sequence quality</h3><img src="{{ $url }}Images/per_base_quality.png" alt="Per base quality graph"></td>
	</tr>
	<tr>
		<td><h3>Per sequence quality scores</h3><img src="{{ $url }}Images/per_sequence_quality.png" alt="Per Sequence quality graph"></td>
	</tr>
	<tr>
		<td><h3>Per base sequence content</h3><img src="{{ $url }}Images/per_base_sequence_content.png" alt="Per base sequence content"></td>
	</tr>
	<tr>
		<td><h3>Per base GC content</h3><img src="{{ $url }}Images/per_base_gc_content.png" alt="Per base GC content graph"></td>
	</tr>
	<tr>
		<td><h3>Per sequence GC content</h3><img src="{{ $url }}Images/per_sequence_gc_content.png" alt="Per sequence GC content graph"></td>
	</tr>
	<tr>
		<td><h3>Per base N content</h3><img src="{{ $url }}Images/per_base_n_content.png" alt="N content graph"></td>
	</tr>
	<tr>
		<td><h3>Sequence Length Distribution</h3><img src="{{ $url }}Images/sequence_length_distribution.png" alt="Sequence length distribution"></td>
	</tr>
	<tr>
		<td><h3>Sequence Duplication Levels</h3><img src="{{ $url }}Images/duplication_levels.png" alt="Duplication level graph"></td>
	</tr>
	<tr>
		<td><h3>Kmer Content</h3><img src="{{ $url }}Images/kmer_profiles.png" alt="Kmer graph"></td>
	</tr>
</table>

<div style="height:50px"></div>
<div class="alert alert-info" style="text-align:center">
	<b>Note: </b>SIMBA uses <a href="http://www.bioinformatics.babraham.ac.uk/projects/download.html#fastqc">FastQC</a> (Babraham Bioinformatics) to generate reports.
</div>

@stop