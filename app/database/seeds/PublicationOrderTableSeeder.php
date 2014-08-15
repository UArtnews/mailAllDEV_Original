<?php

class PublicationOrderTableSeeder extends Seeder {

	public function run()
	{
        DB::table('publication_order')->truncate();

        foreach(Publication::all() as $publication){

            //Write the order to the table
            $publication_order = json_decode($publication->article_order);
            $i = 0;
            foreach($publication_order as $articleID){

                PublicationOrder::create(array(
                    'publication_id'    => $publication->id,
                    'article_id'        => $articleID,
                    'order'             => $i
                ));

                $i += 1;
            }
        }



	}

}
