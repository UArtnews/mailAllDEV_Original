<?php

class InstanceTableSeeder extends Seeder {

	public function run()
	{
		DB::table('instance')->truncate();

		$faker = Faker\Factory::create();

		foreach(range(1,6) as $index)
		{
			Instance::create(array(
				'name'	=> $faker->catchPhrase,
				'created_at'	=> date_format($faker->dateTimeThisYear(), 'Y-m-d H:i:s'),
				'updated_at'	=> date_format($faker->dateTimeThisYear(), 'Y-m-d H:i:s'),
			));
		}
	}
}
