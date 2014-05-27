<?php

class ArticleTableSeeder extends Seeder {

	public function run()
	{
		DB::table('article')->truncate();

		$faker = Faker\Factory::create();

		foreach(range(1,400) as $index)
		{
			Article::create(array(
				'instance_id'	=> Instance::orderBy(DB::raw('RAND()'))->first()->id,
				'title'			=> $faker->catchPhrase,
				'content'		=> $faker->text(900),
				'author_id'		=> User::orderBy(DB::raw('RAND()'))->first()->id,
				'published'		=> 'N',
				'created_at'	=> date_format($faker->dateTimeThisYear(), 'Y-m-d H:i:s'),
				'updated_at'	=> date_format($faker->dateTimeThisYear(), 'Y-m-d H:i:s'),
			));
		}
	}
}
