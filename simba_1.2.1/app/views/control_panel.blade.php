@extends('layouts.master')

@section('conteudo')

<!-- Navegacao -->
<ol class="breadcrumb">
  <li><a href="{{ URL::to('/') }}">Home</a></li>
  <li class="active">Control panel</li>
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

	<p><a href="{{ URL::to('/') }}/control_panel/add_user" class="btn btn-success">New user</a></p>
	<table class="table table-condensed table-bordered">
		<tr>
			<th width="50">ID</th>
			<th>User</th>
			<th width="50">Edit</th>
			<th width="50">Delete</th>
		</tr>
		
		<?php foreach($users as $user){ ?>
			<tr>
				<td style="text-align:center">{{ $user->id }}</td>
				<td>{{ $user->email }}</td>
				<td style="text-align:center">
					<a href="{{ URL::to('/') }}/control_panel/edit_user/{{ $user->id }}">
						<span class="glyphicon glyphicon-pencil"></span>
					</a>
				</td>
				<td style="text-align:center">
					<?php if ($user->id == 1){ ?>
						<a href="#">
							<span class="glyphicon glyphicon-ban-circle"></span>
						</a>
					<?php } else { ?>
					<a href="{{ URL::to('/') }}/control_panel/delete_user/{{ $user->id }}">
						<span class="glyphicon glyphicon-remove"></span>
					</a>
					<?php } ?>
				</td>
			</tr>
		<?php } ?>
	</table>

<?php } else { ?>
	
	<table class="table table-condensed table-bordered">
		<tr>
			<th width="50">ID</th>
			<th>User</th>
			<th width="50">Edit</th>
			<th width="50">Delete</th>
		</tr>
		
		<?php foreach($users as $user){ ?>
			<?php if($user->id == Auth::user()->id){ ?>
				<tr>
					<td style="text-align:center">{{ $user->id }}</td>
					<td>{{ $user->email }}</td>
					<td style="text-align:center">
						<a href="{{ URL::to('/') }}/control_panel/edit_user/{{ $user->id }}">
							<span class="glyphicon glyphicon-pencil"></span>
						</a>
					</td>
					<td style="text-align:center">
						<?php if ($user->id == 1){ ?>
							<a href="#">
								<span class="glyphicon glyphicon-ban-circle"></span>
							</a>
						<?php } else { ?>
						<a href="{{ URL::to('/') }}/control_panel/delete_user/{{ $user->id }}">
							<span class="glyphicon glyphicon-remove"></span>
						</a>
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
		<?php } ?>
	</table>
	
<?php } ?>

<h3><b>System</b></h3>
<pre>
	<?php system("qstat"); ?>
	<?php system("jobs"); ?>
	<?php system("echo '\nLIST OF TEMPORARY FILES (directory . corresponds to [www_folder]/simba/app/assembly/)'"); ?>
	<?php system("echo '\nTMP FILES' && cd ../app/assembly && du -h > list.tmp && grep '_d_tmp' list.tmp"); ?>	
	<?php system("echo '\nCHKPT FILES' && cd ../app/assembly && du -h > list.tmp && grep '_d_chkpt' list.tmp"); ?>
	
</pre>


@stop