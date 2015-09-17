<?php

use Illuminate\Database\Schema\Blueprinteger;
use Illuminate\Database\Migrations\Migration;

class Parameters extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('parameters',function($table3){
			$table3->increments('id_parameter');
			$table3->string('name_parameter',80);
			$table3->string('value_parameters',80);
			$table3->string('type_parameter',20);
			$table3->integer('fk_id_test');
			$table3->timestamps();
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
