<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		//$this->call('UserTableSeeder');
		$this->call('InstanceTableSeeder');
		$this->call('UsersTableSeeder');
		$this->call('ArticleTableSeeder');
		//$this->call('TweakableTableSeeder');
		$this->call('PublicationTableSeeder');
		$this->call('DefaultTweakableTableSeeder');
		$this->call('PublicationOrderTableSeeder');
		$this->call('ImagesTableSeeder');
	}

}
