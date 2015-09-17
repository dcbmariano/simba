<?php

/* ------------------ Application Routes ------------------ */

/* Rotas publicas */
Route::get('login','LoginController@getLogin');
Route::post('login',array('before'=>'csrf','uses'=> 'LoginController@postLogin'));
Route::any('docs', function(){ return View::make('architecture'); });

/* Rotas acessiveis apenas por usuarios logados */
Route::group(array('before' => 'auth'), function(){

	/* Home */
	Route::get('/','HomeController@redirect');
	Route::get('logout','LoginController@logout');
	Route::get('control_panel','HomeController@control_panel');
	Route::any('control_panel/add_user','HomeController@control_panel_add_user');
	Route::any('control_panel/store_user','HomeController@control_panel_store_user');
	Route::get('control_panel/edit_user/{id?}','HomeController@control_panel_edit_user');
	Route::any('control_panel/confirm_edit_user','HomeController@control_panel_confirm_edit_user');
	Route::get('control_panel/delete_user/{id?}','HomeController@control_panel_delete_user');	
	Route::any('control_panel/confirm_delete_user','HomeController@control_panel_confirm_delete_user');
	
	
	/* Projetos */
	Route::any('projects','ProjectsController@index');
	Route::get('projects/create','ProjectsController@create_project');
	Route::post('projects/store','ProjectsController@store_project');
	Route::get('projects/{id?}/assemblies','ProjectsController@list_assemblies');
	Route::get('projects/{id?}','ProjectsController@list_assemblies');
	Route::get('projects/{id?}/generate_sff','ProjectsController@generate_sff');
	Route::get('projects/{id?}/extract_fastq','ProjectsController@extract_fastq');
	Route::get('projects/{id?}/fastqc','ProjectsController@fastqc');	
	Route::get('projects/{id?}/edit','ProjectsController@edit_project');
	Route::post('projects/{id?}/update','ProjectsController@update_project');
	Route::get('projects/{id?}/delete','ProjectsController@delete_project');
	Route::post('projects/{id?}/delete_confirm','ProjectsController@delete_confirm_project');
	Route::get('projects/{id?}/new_assembly/{version?}','ProjectsController@new_assembly');
	Route::get('projects/{id?}/new_quast/{version?}','ProjectsController@new_quast');
	Route::any('projects/{id?}/run_new_assembly','ProjectsController@run_new_assembly');
	Route::any('projects/{id?}/run_new_quast','ProjectsController@run_new_quast');
	
	Route::get('projects/{id_project?}/delete_assembly/{id_assembly?}','ProjectsController@delete_assembly');
	Route::post('projects/{id_project?}/delete_confirm_assembly/{id_assembly?}','ProjectsController@delete_confirm_assembly');
	
	/* Projetos / Trial */
	Route::get('projects/{id?}/assemblies/{id_assembly?}','ProjectsController@list_trial');
	Route::get('projects/{id?}/assemblies/{id_assembly?}/F1','ProjectsController@f1');
	Route::any('projects/{id?}/assemblies/{id_assembly?}/F1/run','ProjectsController@run_f1'); 
	Route::get('projects/{id?}/assemblies/{id_assembly?}/F2','ProjectsController@f2');	
	Route::any('projects/{id?}/assemblies/{id_assembly?}/F2/run','ProjectsController@run_f2'); 
	Route::get('projects/{id?}/assemblies/{id_assembly?}/F3','ProjectsController@f3');	
	Route::any('projects/{id?}/assemblies/{id_assembly?}/F3/run','ProjectsController@run_f3');
	Route::any('projects/{id?}/assemblies/{id_assembly?}/F3/run_part','ProjectsController@run_f3_part');
	Route::get('projects/{id?}/assemblies/{id_assembly?}/F4','ProjectsController@f4');	
	Route::any('projects/{id?}/assemblies/{id_assembly?}/F4/run','ProjectsController@run_f4');
	Route::any('projects/{id?}/assemblies/{id_assembly?}/F4/run_part','ProjectsController@run_f4_part');	
	Route::get('projects/{id?}/assemblies/{id_assembly?}/F5','ProjectsController@f5');	
	Route::any('projects/{id?}/assemblies/{id_assembly?}/F5/run','ProjectsController@run_f5');	
	
	/* Modelo */
	Route::get('task/add', 'TaskController@getAdd');
	Route::post('task/add', 'TaskController@postAdd');
	Route::any('task', 'TaskController@listar');
	Route::any('tasks','TaskController@listar');
	Route::post('task/check', 'TaskController@check');
	
	/* Actions */
	Route::get('action/update_projects','ActionController@update_projects_list');
	Route::get('action/update_assemblies_info/{id?}','ActionController@update_assemblies_info');
	Route::get('action/bam2sff/{name_folder?}','ActionController@bam2sff');
	Route::get('action/sff_extract/{name_folder?}','ActionController@sff_extract');
	Route::get('action/fastqc/{name_folder?}','ActionController@fastqc');
	
	/* Tools */
	Route::get('tools','ToolController@list_tools');
	Route::get('tools/supercontigs','ToolController@supercontigs');
	Route::get('tools/webcontiguator','ToolController@webcontiguator');
	Route::any('tools/run_webcontiguator','ToolController@run_webcontiguator');
	Route::get('tools/webcontiguator_view_result','ToolController@webcontiguator_view_result');
	Route::get('tools/legoscaffold','ToolController@legoscaffold');
	Route::get('tools/scaffoldhibrido','ToolController@scaffoldhibrido');
	
});