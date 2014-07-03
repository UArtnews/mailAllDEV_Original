<?php

class Article extends Eloquent {
	protected $guarded = array('id');

	protected $table = 'article';

    protected $softDelete = true;

	public $timestamps = true;
}
