<?php

class InstanceTableSeeder extends Seeder {

	public function run()
	{
		DB::table('instance')->truncate();

		$faker = Faker\Factory::create();

		foreach(range(1,3) as $index)
		{
			Instance::create(array(
				'name'	=> $faker->lastName,
				'created_at'	=> date_format($faker->dateTimeThisYear(), 'Y-m-d H:i:s'),
				'updated_at'	=> date_format($faker->dateTimeThisYear(), 'Y-m-d H:i:s'),
			));
		}
	}
}