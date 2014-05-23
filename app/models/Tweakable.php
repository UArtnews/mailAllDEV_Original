<?php

class Tweakable extends Eloquent {
	
	protected $guarded = array('id');

	protected $table = 'tweakable';

	public $timestamps = true;

	public function instance(){
		return $this->bleongsTo('Instance', 'instance_id', 'id');
	}

}