<?php

class Tweakable extends Eloquent {
	
	protected $guarded = array('id');

	protected $table = 'tweakable';

	public $timestamps = false;

	public function instance(){
		return $this->bleongsTo('Instance', 'instance_id', 'id');
	}

}