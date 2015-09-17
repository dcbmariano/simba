<?php

class Project extends Eloquent {
	
	protected $table = 'projects';
	protected $primaryKey = 'id_project';
	
	public static $regras = array('name_project'=>'required|unique:projects',
						'organism_project'=>'required',
						'ngs_project'=>'required',
						'library_project'=>'required');
	
	public static $regras_update = array('organism_project'=>'required',
						'ngs_project'=>'required',
						'library_project'=>'required');
						
	protected function getProject($id){
		return DB::table('projects')->where('id_project',$id)->first();
	}
	
	protected function getProjectName($name){
		return DB::table('projects')->where('name_project',$name)->first();
	}
	
	protected function updateProject($input){
		DB::table('projects')->where('id_project',$input['id_project'])
										->update(array(
										'organism_project' => $input['organism_project'],
										'ngs_project' => $input['ngs_project'],
										'library_project' => $input['library_project']
		));
	}
	
	protected function updateProjectArray($project,$info){
		DB::table('projects')->where('id_project',$project->id_project)
										->update(array(
										'BAM' => $info['BAM'],
										'SFF' => $info['SFF'],
										'FASTQ' => $info['FASTQ'],
		 								'assembly' => $info['Assembly']
		));
	}
	
	protected function deleteProject($id){
		DB::table('projects')->where('id_project', $id)->delete();
	}
	
	public function assemblies(){
		return $this->hasMany('Assembly','fk_id_project','id_project');
	}

	public function curations(){
		return $this->hasMany('Curation','fk_id_project','id_project');
	}

}