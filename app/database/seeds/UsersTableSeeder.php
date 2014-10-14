<?php

class UsersTableSeeder extends Seeder {

	public function run()
	{
		DB::table('users')->truncate();

		$faker = Faker\Factory::create();

		foreach(range(1,180) as $index)
		{
			User::create(array(
				'email'			=> $faker->email,
				'uanet'			=> $faker->lexify('???????'),
				'first'			=> $faker->firstName,
				'last'			=> $faker->lastName,
				'created_at'	=> date_format($faker->dateTimeThisYear(), 'Y-m-d H:i:s'),
				'updated_at'	=> date_format($faker->dateTimeThisYear(), 'Y-m-d H:i:s'),
			));
		}
	}
}
