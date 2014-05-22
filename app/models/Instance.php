<?php

class Instance extends Eloquent {
	
	protected $guarded = array('id');

	protected $table = 'instance';

	public $timestamps = false;

	public function tweakables(){
		return $this->hasMany('Tweakable', 'instance_id', 'id');
	}

}