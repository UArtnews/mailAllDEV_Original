<?php

class PublicationImporter extends Seeder {

    public function run()
    {
        $zipmailInstance = Instance::where('name', 'Zipmail')->first();
        $wayneInstance = Instance::where('name', 'Waynemail')->first();
        $digestInstance = Instance::where('name', 'Digest')->first();

        DB::table('article')->truncate();
        DB::table('publication')->truncate();
        DB::table('publication_order')->truncate();

        //Entire Digest Process
        $articleMap = array();
        $pubMap = array();

        $everything = DB::connection('edigest')
            ->select('SELECT * FROM item_sequence
                join edigest_items on item_sequence.i_id = edigest_items.id
                join edigest_header on item_sequence.e_id = edigest_header.digest_id'
            );

        $total = count($everything);

        echo "\nDigest Progress:     ";

        //Do it
        foreach($everything as $current => $thing){
            //Each Article
            if(!isset($articleMap[$thing->i_id])) {
                $newArticle = new Article;

                $newArticle->instance_id = $digestInstance->id;
                $newArticle->title = html_entity_decode($thing->title);
                $newArticle->content = html_entity_decode($thing->story);
                $newArticle->author_id = 1;
                $newArticle->published = $thing->old;
                $newArticle->submission = 'N';
                $newArticle->created_at = $thing->date;
                $newArticle->issue_dates = json_encode($thing->digest_date);

                $newArticle->save();
                $articleMap[$thing->i_id] = $newArticle->id;
            }

            //Each publication
            if(!isset($pubMap[$thing->digest_id])){
                $newPub = new Publication;

                $newPub->instance_id = $digestInstance->id;
                $newPub->publish_date = $thing->digest_date;
                $newPub->banner_image = 'http://www.uakron.edu/digest/images/digest-header-v1.jpg';
                $newPub->published = $thing->current_status == 'Live' ? 'Y' : 'N';
                $newPub->type = strtolower($thing->digest_type);

                $newPub->save();
                $pubMap[$thing->digest_id] = $newPub->id;
            }

            //Each Order
            $newOrder = new PublicationOrder;

            $newOrder->publication_id = $pubMap[$thing->e_id];
            $newOrder->article_id = $articleMap[$thing->i_id];
            $newOrder->likeNew = $thing->location == 'N' ? 'Y' : 'N';
            $newOrder->order = $thing->sequence - 1;

            $newOrder->save();

            $this->drawProgress($current, $total);
        }

        //Entire Zipmail Process
        $articleMap = array();
        $pubMap = array();

        DB::reconnect('zipmail');
        $everything = DB::connection('zipmail')
            ->select('Select * from email_entries
                join entries on entries.id = email_entries.entry
                join email on email.id = email_entries.email
                where email.owner = \'UA\'');

        $total = count($everything);

        echo "\nZipmail Progress:     ";

        //Do it
        foreach($everything as $current => $thing){
            //Each Article
            if(!isset($articleMap[$thing->entry])) {
                $newArticle = new Article;

                $newArticle->instance_id = $zipmailInstance->id;
                $newArticle->title = $thing->shortdesc;
                $newArticle->content = $thing->longdesc;
                $newArticle->author_id = 1;
                $newArticle->published = 'N';
                $newArticle->submission = 'Y';
                $newArticle->created_at = $thing->submitted;
                $newArticle->updated_at = $thing->updated;
                $newArticle->issue_dates = json_encode($thing->week);

                $newArticle->save();
                $articleMap[$thing->entry] = $newArticle->id;
            }
            //Each publication
            if(!isset($pubMap[$thing->email])){
                $newPub = new Publication;

                $newPub->instance_id = $zipmailInstance->id;
                $newPub->publish_date = $thing->date;
                $newPub->banner_image = '';
                $newPub->published = $thing->sent != null ? 'Y' : 'N';
                $newPub->type = $thing->email_type == 'N' ? 'regular' : 'special';

                $newPub->save();
                $pubMap[$thing->email] = $newPub->id;
            }

            //Each Order
            $newOrder = new PublicationOrder;

            $newOrder->publication_id = $newPub->id;
            $newOrder->article_id = $newArticle->id;
            $newOrder->likeNew = 'Y';
            $newOrder->order = $thing->sequence - 1;

            $newOrder->save();

            $this->drawProgress($current, $total);
        }

        //Entire Wayne Process
        $articleMap = array();
        $pubMap = array();

        DB::reconnect('zipmail');
        $everything = DB::connection('zipmail')
            ->select('Select * from email_entries
                join entries on entries.id = email_entries.entry
                join email on email.id = email_entries.email
                where email.owner = \'Wayne\'');

        $total = count($everything);

        echo "\nWaynemail Progress:     ";

        //Do it
        foreach($everything as $current => $thing) {
            //Each Article
            if (!isset($articleMap[$thing->entry])) {
                $newArticle = new Article;

                $newArticle->instance_id = $wayneInstance->id;
                $newArticle->title = $thing->shortdesc;
                $newArticle->content = $thing->longdesc;
                $newArticle->author_id = 1;
                $newArticle->published = 'N';
                $newArticle->submission = 'Y';
                $newArticle->created_at = $thing->submitted;
                $newArticle->updated_at = $thing->updated;
                $newArticle->issue_dates = json_encode($thing->week);

                $newArticle->save();
                $articleMap[$thing->entry] = $newArticle->id;
            }
            //Each publication
            if (!isset($pubMap[$thing->email])) {
                $newPub = new Publication;

                $newPub->instance_id = $wayneInstance->id;
                $newPub->publish_date = $thing->date;
                $newPub->banner_image = '';
                $newPub->published = $thing->sent != null ? 'Y' : 'N';
                $newPub->type = $thing->email_type == 'N' ? 'regular' : 'special';

                $newPub->save();
                $pubMap[$thing->email] = $newPub->id;
            }

            //Each Order
            $newOrder = new PublicationOrder;

            $newOrder->publication_id = $newPub->id;
            $newOrder->article_id = $newArticle->id;
            $newOrder->likeNew = 'Y';
            $newOrder->order = $thing->sequence - 1;

            $newOrder->save();

            $this->drawProgress($current, $total);
        }

    }

    public function drawProgress($part, $total){
        echo "\033[5D";
        echo str_pad(round($part/$total*100), 3, ' ', STR_PAD_LEFT) . " %";
    }
}