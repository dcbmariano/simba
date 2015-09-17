<!doctype html>
<html lang="pt-br">
	<head>

		<meta charset="UTF-8">
		<title>SIMBA</title>

		<!-- CSS -->
		<link type="text/css" rel="stylesheet" href="{{ URL::to('/') }}/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="{{ URL::to('/') }}/css/bootstrap-theme.min.css" />
		<link type="text/css" rel="stylesheet" href="{{ URL::to('/') }}/css/style.css" />
		<!-- End -->	

		<!-- Favicon -->
		<link rel="shortcut icon" href="{{ URL::to('/') }}/img/favicon.png" />
		<!-- End -->

		<!-- Codigos relacionados a compatibilidade com IE -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- End -->

	</head>

	<body>

		<!-- Header -->
		<header>
			<div id="header1">
				 <div class="container">
				 	<div class="col-lg-1" style="text-align:center;float:left">
			        	<a style="color:#111;font-weight: bolder;" href="{{ URL::to('/') }}">HOME</a>
			        </div>
			        <div class="col-lg-1" style="text-align:center;float:left">
			        	<a style="color:#111;font-weight: bolder;" href="{{ URL::to('/') }}/docs">DOCS</a>
			        </div>
			        <div class="col-lg-1" style="text-align:center;float:left">
			        	<a style="color:#111;font-weight: bolder;" href="{{ URL::to('/') }}/tools">TOOLS</a>
			        </div>
			        <div class="col-lg-1" style="text-align:center;float:left">
			        	<a style="color:#111;font-weight: bolder;" data-toggle="modal" data-target="#sobre" href="#sobre">ABOUT</a>
			        </div>
			        <div class="col-lg-3" style="text-align:right;float:right">
			        	<?php if(isset(Auth::user()->email)){ ?>
			        		<b>
			        			<a style="color:#111" href="{{ URL::to('/') }}/control_panel">
			        				<span class="glyphicon glyphicon-user"></span> {{ strtoupper(Auth::user()->email) }}
			        			</a>  |  
			        		</b>
			        		<a style="color:#111;font-weight: bolder;" href="{{ URL::to('logout') }}">LOGOUT</a>
			        	<?php } ?>
			        </div>
			        <div style="clear:both"></div>
			        
				 </div>
			</div>
			<div id="header2">
				<div class="container">
				 	<div style="float:left">
		      			<a href="{{ URL::to('/') }}"><img src="{{ URL::to('/') }}/img/logo.png" /></a>
		      		</div>
				</div>
				<div style="clear:both"></div>
			</div>
		</header>
		<!-- End -->

		<!-- Conteudo -->
		<div class="container">
			<div class="content">
				@yield('conteudo')
			</div>
		</div>
		<!-- End -->		

		<!-- Modal SOBRE -->
		<div class="modal fade" id="sobre" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
		    	<div class="modal-content">
		    		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        		<h4 class="modal-title" id="myModalLabel"><b>Simba</b></h4>
		      		</div>
		    		<div class="modal-body">
		      			<center><img src="{{ URL::to('/') }}/img/logo_inverse.png" /></center>
		        		<b>Version:</b> 1.2.1 final<br>
		        		<b>Author:</b> DCB Mariano, FL Pereira, et al.
		      		</div>
		      		<div class="modal-footer">
		        		<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
		      		</div>
		    	</div><!-- /.modal-content -->
		  	</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<!-- End -->

		<!-- Rodape -->
		<footer>
			<div style="text-align: center">
				<img src="{{ URL::to('/') }}/img/minilogo.png" />
			</div>
			<div class="container" style="text-align:left;padding-top:100px;font-size:12px;color:#aaa">
				SIMBA version final by <a href="http://lgcm.icb.ufmg.br" class="navbar-link">LGCM</a> | Univesidade Federal de Minas Gerais | 2014
			</div>
			<script src="{{ URL::to('/') }}/js/jquery.js"></script>
			<script src="{{ URL::to('/') }}/js/bootstrap.min.js"></script>
			@section('custom_script')
			@show
		</footer>
		<!-- End -->
	</body>
</html>
