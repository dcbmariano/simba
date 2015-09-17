<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Projects extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('projects',function($table){
			$table->increments('id_project');
			$table->string('name_project',100);			
			$table->string('organism_project',100);
			$table->string('ngs_project',30);
			$table->string('library_project',30);
			$table->integer('bam');
			$table->integer('sff');
			$table->integer('fastq');
			$table->integer('assembly');
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
		Schema::dropIfExists('projects');
	}

}
