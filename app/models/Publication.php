<?php

class Publication extends Eloquent {
	protected $guarded = array('id');

	protected $table = 'publication';

	public $timestamps = true;

    public function articles()
    {
        return $this->belongsToMany('Article','publication_order');
    }

    public function instance()
    {
        return $this->belongsTo('Instance');
    }

}
