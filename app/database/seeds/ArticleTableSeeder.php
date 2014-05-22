<?php

class ArticleTableSeeder extends Seeder {

	public function run()
	{
		DB::table('users')->truncate();

		$faker = Faker\Factory::create();

		foreach(range(1,20) as $index)
		{
			User::create(array(
				'instance_id'	=> Instance::orderBy(DB::raw('RAND()'))->first()->id,
				'title'			=> $faker->catchPhrase,
				'content'		=> $faker->text(900),
				'author_id'		=> User::orderBy(DB::raw('RAND()'))->first()->id,
				'published'		=> 'N',
			));
		}
	}
}
