<?php

class ProjectsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('projects')->delete();

        Project::create(array(
            'name_project' => 'cp31',
            'organism_project' => 'Corynebacterium pseudotuberculosis 31',
			'ngs_project' => 'iontor',
			'library_project' => 'Fragment',
			'bam' => 1,
			'sff' => 0,
			'fastq' => 0,
			'assembly' => 0			
        ));
		
		Project::create(array(
            'name_project' => 'llmcdo',
            'organism_project' => '',
			'ngs_project' => '',
			'library_project' => '',
			'bam' => 0,
			'sff' => 0,
			'fastq' => 0,
			'assembly' => 0			
        ));
    }
}