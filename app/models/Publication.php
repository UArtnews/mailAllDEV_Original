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

    public function hasRepeat(){
        foreach($this->articles as $article){
            if($article->isPublished($this->id) && $article->likeNew($this->id) !== 'Y'){
                return true;
            }
        }
        return false;
    }

}
