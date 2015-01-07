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

    public function scopeLivePublication($query){
        $query->published()->
        regular()->
        orderBy('publish_date', 'desc')->
        withArticles();
    }

    public function scopePublished($query){
        $query->where('published','Y');
    }

    public function scopeRegular($query){
        $query->where('type','regular');
    }

    public function scopeWithArticles($query){
        $query->with(
            array(
                'articles' => function ($query) {
                    $query->orderBy('order', 'asc');
                }
            )
        );
    }

    public function scopeMostRecent($query, $count){
        $query->where('publish_date','>',date('Y-m-d'))
            ->orderBy('publish_date','ASC')
            ->limit($count);
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
