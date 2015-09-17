<?php

class Curation extends Eloquent{
	
	protected $table = 'curations';
	protected $primaryKey = 'id_curation';
		
	public function assembly(){
		return $this->belongTo('Assembly','id_assembly');
	}
	
	public function project(){
		return $this->belongTo('Project','id_project');
	}
	
	protected function getCuration($id_assembly,$id_project,$version){
		return DB::table('curations')->where('fk_id_assembly', $id_assembly)->where('fk_id_project', $id_project)->where('version_curation',$version)->orderBy('id', 'DESC')->first();
	}
	
	protected function getCurationByProject($id_project){
		return DB::table('curations')->where('fk_id_project',$id_project)->first();
	}
	
}