@extends('layouts.master')

@section('conteudo')

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li><a href="{{ URL::to('control_panel') }}">Control panel</a></li>
  <li class="active">Edit user</li>
</ol>
<!-- Fim navegacao -->

<?php /* Admin pode editar todos */ ?>
<?php if(strtoupper(Auth::user()->email) == 'ADMIN'){ ?>

	<form action="../confirm_edit_user" method="post">
		
		<input type="hidden" name="id_user" value="{{ $user->id }}" />
	
		<label class="label label-danger">User</label>
		<input type="text" class="form-control" name="user_email" value="{{ $user->email }}" disabled /><br/><br/>
		
		<label class="label label-danger">Password</label>
		<input type="password" name="user_password" placeholder="Type a new password" class="form-control" value=""/><br/>
		
		<input type="submit" value="Update" class="btn btn-danger" />
		<br/>
	
	</form>

<?php /* Apenas o usuario pode editar sua propria pagina */ ?>
<?php } elseif(strtoupper(Auth::user()->email) == strtoupper($user->email)) { ?>
		
	<form action="../confirm_edit_user" method="post">
		
		<input type="hidden" name="id_user" value="{{ $user->id }}" />
	
		<label class="label label-danger">User</label>
		<input type="text" class="form-control" name="user_email" value="{{ $user->email }}" disabled /><br/><br/>
		
		<label class="label label-danger">Password</label>
		<input type="password" name="user_password" placeholder="Type a new password" class="form-control" value=""/><br/>
		
		<input type="submit" value="Update" class="btn btn-danger" />
		<br/>
	
	</form>
	
<?php 
/* Bloqueie o acesso de todos os outros */
} else {
	echo "Sorry. You don't have permission to see this page.";	
}
?>


@stop
