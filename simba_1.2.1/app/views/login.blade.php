@extends('layouts.master')

@section('conteudo')

<!-- Setor de erros -->
@if ( count($errors) > 0)
<br/>
<div class="alert alert-danger">
	@foreach($errors->all() as $e)
		<p style="padding:5px">{{ $e }}</p>
	@endforeach
</div>
@endif

<div class="row-fluid marketing" style="padding:50px 0 120px 0">
	<center><h3><b>Login</b></h3><br/></center>
    <div class="span6">
        
        <div class="row">
			<div class="col-md-8">
				<form class="form-horizontal" role="form" method="post" action="{{ URL::to('login') }}">
					<div class="form-group">
					    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
					    <div class="col-sm-10">
					    	<input type="text" name="email" class="form-control" id="inputEmail3" placeholder="Email">
					    </div>
					</div>
					<div class="form-group">
					    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
					    <div class="col-sm-10">
					    	<input type="password" class="form-control" name="senha" id="inputPassword3" placeholder="Password">
					    </div>
					</div>
					<div class="form-group">
					    <div class="col-sm-offset-2 col-sm-10">
		                	<input type="hidden" name="_token" value="{{ csrf_token() }}" />
					    	<input type="submit" class="btn btn-danger btn-block" value="Login">
					    </div>
					</div>
				</form>    
			</div>
			<div class="col-md-4">
				<img src="{{ URL::to('/')}}/img/logo_inverse.png" />
			</div>    
		</div>
        
    </div>
</div>

@stop