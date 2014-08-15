<?php

class PrimaryTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testInstanceRoutes()
	{
        $instances = Instance::all();

        echo "\nTesting main routes";
        foreach($instances as $instance){
            //Test Editor Root
            $this->call('GET',URL::to('edit/'.$instance->name));
            $this->assertResponseOk();

            //Test Articles Tab
            $this->call('GET',URL::to('edit/'.$instance->name.'/articles'));
            $this->assertResponseOk();

            //Test Publications Tab
            $this->call('GET',URL::to('edit/'.$instance->name.'/publications'));
            $this->assertResponseOk();

            //Test Images Tab
            $this->call('GET',URL::to('edit/'.$instance->name.'/images'));
            $this->assertResponseOk();

            //Test Settings Tab
            $this->call('GET',URL::to('edit/'.$instance->name.'/settings'));
            $this->assertResponseOk();

            //Test Images JSON
            $this->call('GET',URL::to('json/'.$instance->name.'/images'));
            $this->assertResponseOk();
        }
	}

}