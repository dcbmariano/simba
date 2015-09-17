@extends('layouts.master')

@section('conteudo')

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li><a href="{{ URL::to('/') }}/control_panel">Control panel</a></li>
  <li class="active">Delete user</li>
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

<?php 
if($id == 1){
	echo "You can't delete the admin!";
}
else {
?>

<form role="form" method="POST" action="{{ URL::to('control_panel') }}/confirm_delete_user">
	<input type="hidden" name="id_user" value="{{ $id }}" />
	<h3>Confirm deletion of user id {{ $id }}?</h3>
	<br/>
	<a href="{{ URL::to('control_panel') }}" class="btn btn-default">Cancel</a>
	<input type="submit" value="Delete" class="btn btn-danger"/>
</form>

<?php } ?>

@stop