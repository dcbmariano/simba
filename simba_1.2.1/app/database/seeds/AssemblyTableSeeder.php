<?php

class ProjectsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('assemblies')->delete();

        Assembly::create(array(
            'version_assembly' => 1,
            'fk_id_project' => 2,
			'num_contigs_assembly' => 0,
			'len_genome_assembly' => 0,
			'min_contig_assembly' => 0,
			'max_contig_assembly' => 0,
			'info_assembly' => '',
			'status_assembly' => 'Q'			
        ));
		Assembly::create(array(
            'version_assembly' => 1,
            'fk_id_project' => 1,
			'num_contigs_assembly' => 0,
			'len_genome_assembly' => 0,
			'min_contig_assembly' => 0,
			'max_contig_assembly' => 0,
			'info_assembly' => '',
			'status_assembly' => 'Q'			
        ));
    }
}
