<?php

class Parameter extends Eloquent{
	
	protected $table = 'parameters';
	protected $primaryKey = 'id_parameter';

	public function assembly(){
		return $this->belongTo('Assembly','id_assembly');
	}
	
}