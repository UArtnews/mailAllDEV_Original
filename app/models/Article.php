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

    public function isPublished($thisPublicationId = '')
    {
        $count = DB::table('publication')
            ->join('publication_order','publication.id','=','publication_order.publication_id')
            ->where('publication.published','=','Y')
            ->where('publication_order.article_id','=',$this->id)
            ->count();

        if($thisPublicationId == $this->originalPublication()){
            return false;
        }elseif($count > 0){
            return true;
        }else{
            return false;
        }
    }

    public function publishCount(){
        return DB::table('publication')
            ->join('publication_order','publication.id','=','publication_order.publication_id')
            ->where('publication.published','=','Y')
            ->where('publication_order.article_id','=',$this->id)
            ->count();
    }

    public function likeNew($thisPublicationId = '')
    {
        return DB::table('publication_order')
            ->where('publication_order.publication_id','=',$thisPublicationId)
            ->where('publication_order.article_id','=',$this->id)
            ->pluck('likeNew');
    }

    public function originalPublication()
    {
        return DB::table('publication')
            ->join('publication_order','publication.id','=','publication_order.publication_id')
            ->where('publication.published','=','Y')
            ->where('publication_order.article_id','=',$this->id)
            ->orderBy('publication.publish_date','ASC')
            ->pluck('publication.id');
    }

    public function originalPublishDate()
    {
        return DB::table('publication')
            ->join('publication_order','publication.id','=','publication_order.publication_id')
            ->where('publication.published','=','Y')
            ->where('publication_order.article_id','=',$this->id)
            ->orderBy('publication.publish_date','ASC')
            ->pluck('publication.publish_date');
    }

}