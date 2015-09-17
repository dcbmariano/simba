@extends('layouts.master')

@section('conteudo')

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li><a href="{{ URL::to('/') }}/control_panel">Control panel</a></li>
  <li class="active">Add user</li>
</ol>
<!-- Fim navegacao -->

<h3>Welcome, {{ strtoupper(Auth::user()->email) }}!</h3>
<br/>

<!-- Setor de erros -->
@if ( count($errors) > 0)
<br/>
<div class="alert alert-danger">
	@foreach($errors->all() as $e)
		<p style="padding:5px">{{ $e }}</p>
	@endforeach
</div>
@endif
			
<?php	/* Apenas o admin pode visualizar o conteudo abaixo */ ?>
<?php if(strtoupper(Auth::user()->email) == 'ADMIN'){ ?>

	<div class="form-group">
		<form action="store_user" method="post" autocomplete="off">
			<label class="label label-default">E-mail (user name):</label>
			<input type="text" name="email" class="form-control"/>
			<label class="label label-default" >Password:</label>
			<input type="password" name="password" class="form-control"/>
			<br/>
			<input type="submit" value="Add" class="btn btn-danger" />
		</form>
	</div>

<?php } ?>

<br/><br/>

<div class="alert alert-info" style="text-align: center; font-family: Arial;">
	Just ADMIN can add users.
</div>

@stop