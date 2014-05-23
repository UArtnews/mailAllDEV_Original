<?php

class Publication extends Eloquent {
	protected $guarded = array('id');

	protected $table = 'publication';

	public $timestamps = true;
}
