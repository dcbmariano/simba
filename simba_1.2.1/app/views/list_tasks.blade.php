@extends('layouts.master')
@section('conteudo')
<!-- Insira o conteudo da pagina aqui -->


<a href="{{ URL::to('/') }}/task/add" class="btn btn-success">Adicionar nova tarefa</a>

<p><br/></p>

<table class="table table-bordered">
	<th>Tarefa: </th>
	<th>Status: </th>
	@foreach($tasks as $t)
		<tr>
			<td>{{ $t->titulo }}</td>
			@if ($t->status)
				<td><span class="label label-success">OK</span></td>
			@else
				<td><label data-task-id="{{ $t->id }}"><input type="checkbox" /></label></td>
			@endif	
		</tr>
	@endforeach
</table>

@if ( count($errors) > 0)
	<span class="label label-danger">Error: </span>
	@foreach($errors->all() as $e)
		<p><br/>{{ $e }}</p>
	@endforeach
@endif

@if (isset($sucesso))
	<span class="label label-success">Adicionado com sucesso.</span>
@endif

<!-- End -->
@stop

@section('custom_script')
	<script language="javascript">
		$(document).ready(function(){ 
			$('td label input').on('change', function(){
				var task_id = $(this).parent().data('task-id');
				var td = $(this).parent().parent();

				// ajax post request
				$.post(
					"/task/check",
					{task_id: task_id},
					function(data){
						// callback do ajax request
						if(data.status == true){
							td.html("<span class='label label-success'>OK</span>");
						}
					}
				);
			});
		});
	</script>
@stop