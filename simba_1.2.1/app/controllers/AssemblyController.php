<?php

class FastqcController extends BaseController{
	
	public function index($id = null){
		
		if ($id == null){
			return Redirect::to('projects')->withErrors(array('Project not found.'));
		}
		
		
		#system("cd /home/diego/montagem/$project && mkdir fastqc && fastqc *.fastq -o fastqc");
		
		return View::make("assembly/$id")->with('id',$id);
		
	}
	
	
} 