<?php

use Illuminate\Database\Schema\Blueprinteger;
use Illuminate\Database\Migrations\Migration;

class Tests extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('tests',function($table){
			$table->increments('id_test');
			$table->integer('version_test');
			$table->integer('fk_id_project');
			$table->integer('num_contigs_test');
			$table->integer('len_genome_test');
			$table->integer('min_contig_test');
			$table->integer('max_contig_test');
			$table->integer('n50_test');
			$table->text('info_test');
			$table->string('status_test',1);
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
