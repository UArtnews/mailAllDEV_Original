<?php

class PublicationOrderTableSeeder extends Seeder {

	public function run()
	{
        DB::table('publication_order')->truncate();

        foreach(Publication::all()->orderBy('publish_date','ASC') as $publication){
            $articles = array();
            foreach(range(1,rand(5,12)) as $articleIndex)
            {
                $article = Article::orderBy(DB::raw('RAND()'))->first();
                array_push($articles, $article->id);
            }

            //Write the order to the table
            $i = 0;
            foreach($articles as $article){

                PublicationOrder::create(array(
                    'publication_id'    => $publication->id,
                    'article_id'        => $article->id,
                    'order'             => $i
                ));

                $i += 1;
            }
        }



	}

}
