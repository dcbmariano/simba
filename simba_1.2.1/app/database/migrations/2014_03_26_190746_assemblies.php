<?php

use Illuminate\Database\Schema\Blueprinteger;
use Illuminate\Database\Migrations\Migration;

class Assemblies extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		
		Schema::create('assemblies',function($table){
			$table->increments('id_assembly');
			$table->integer('version_assembly');
			$table->integer('fk_id_project');
			$table->integer('num_contigs_assembly');
			$table->integer('len_genome_assembly');
			$table->integer('min_contig_assembly');
			$table->integer('max_contig_assembly');
			$table->integer('n50_assembly');
			$table->text('info_assembly');
			$table->string('status_assembly',1);
			$table->timestamps();
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
