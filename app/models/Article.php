<?php

class Article extends Eloquent {
	protected $guarded = array('id');

	protected $table = 'article';

	public $timestamps = true;
}
