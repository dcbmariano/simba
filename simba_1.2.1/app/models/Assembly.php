<?php

class Assembly extends Eloquent{
	
	protected $table = 'assemblies';
	protected $primaryKey = 'id_assembly';
	
	public function getAssemblies($id){
		return belongTo('Project','id_project');
	}
	
	public function project(){
		return $this->belongTo('Project','id_project');
	}
	
	public function curations(){
		return $this->hasMany('Curation','fk_id_assembly','id_assembly');
	}
	
	protected function updateAssemblyArray($id,$info){
		DB::table('assemblies')->where('id_assembly',$id)
										->update(array(
										'min_contig_assembly' => $info['min'],
										'max_contig_assembly' => $info['max'],
										'n50_assembly' => $info['n50'],
		 								'len_genome_assembly' => $info['len_genome'],
		 								'num_contigs_assembly' => $info['num_contigs'],
		 								'info_assembly' => $info['info']
		));
	}
	
	protected function getIdAssembly($id_project,$version){
		return DB::table('assemblies')->where('version_assembly', $version)->where('fk_id_project', $id_project)->first();
	}
	
	protected function deleteAssembly($id_assembly){
		DB::table('assemblies')->where('id_assembly', $id_assembly)->delete();
	}
	
}