<?php

class Article extends Eloquent {
	protected $guarded = array('id');

	protected $table = 'article';

    protected $softDelete = true;

	public $timestamps = true;

    //Statics to improve querying
    private $isPublished;
    private $publishCount;
    private $likeNew;
    private $originalPublication;
    private $originalPublishDate;

    public function publications()
    {
        return $this->belongsToMany('Publication','publication_order');
    }

    public function isPublished($thisPublicationId = '')
    {
        if($this->isPublished != null){
            return $this->isPublished;
        }else{
            $count = $this->publishCount();

            if($thisPublicationId == $this->originalPublication()){
                $this->isPublished = false;
            }elseif($count > 0){
                $this->isPublished = true;
            }else{
                $this->isPublished = false;
            }

            return $this->isPublished;
        }
    }

    public function publishCount(){
        if($this->publishCount != null){
            return $this->publishCount;
        }else{
            $this->publishCount = DB::table('publication')
                ->join('publication_order','publication.id','=','publication_order.publication_id')
                ->where('publication.published','=','Y')
                ->where('publication_order.article_id','=',$this->id)
                ->count();
            return $this->publishCount;
        }
    }

    public function likeNew($thisPublicationId = '')
    {
        if($this->likeNew != null){
            return $this->likeNew;
        }else{
            $this->likeNew = DB::table('publication_order')
                ->where('publication_order.publication_id','=',$thisPublicationId)
                ->where('publication_order.article_id','=',$this->id)
                ->pluck('likeNew');
            return $this->likeNew;
        }
    }

    public function originalPublication()
    {
        if($this->originalPublication != null){
            return $this->originalPublication;
        }else{
            $this->originalPublication = DB::table('publication')
                ->join('publication_order','publication.id','=','publication_order.publication_id')
                ->where('publication.published','=','Y')
                ->where('publication_order.article_id','=',$this->id)
                ->orderBy('publication.publish_date','ASC')
                ->pluck('publication.id');
            return $this->originalPublication;
        }
    }

    public function originalPublishDate()
    {
        if($this->originalPublishDate != null){
            return $this->originalPublishDate;
        }else{
            $this->originalPublishDate = DB::table('publication')
                ->join('publication_order','publication.id','=','publication_order.publication_id')
                ->where('publication.published','=','Y')
                ->where('publication_order.article_id','=',$this->id)
                ->orderBy('publication.publish_date','ASC')
                ->pluck('publication.publish_date');
            return $this->originalPublishDate;
        }
    }
}