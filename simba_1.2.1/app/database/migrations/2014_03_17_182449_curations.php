<?php

use Illuminate\Database\Schema\Blueprinteger;
use Illuminate\Database\Migrations\Migration;

class Curations extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('curations',function($table4){
			$table4->increments('id_curation');
			$table4->integer('fk_id_test');
			$table4->integer('version_curation');
			$table4->text('report_curation');
			$table4->integer('num_scaffolds');
			$table4->integer('len_genome_curation');
			$table4->integer('min_contig-curation');
			$table4->integer('max_contig_curation');
			$table4->integer('n50_curation');
			$table4->timestamps();
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
