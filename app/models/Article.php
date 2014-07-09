<?php

class Article extends Eloquent {
	protected $guarded = array('id');

	protected $table = 'article';

    protected $softDelete = true;

	public $timestamps = true;

    public function publications()
    {
        return $this->belongsToMany('Publication','publication_order');
    }

}