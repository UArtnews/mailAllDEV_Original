<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function index()
	{

		$data = array(
			'instances' => Instance::all(),
		);

		foreach($data['instances'] as $instance){
            if(Tweakable::where('instance_id',$instance->id)->where('parameter', 'publication-banner-image')->count() > 0){
                $instance->banner_image_url = Tweakable::where('instance_id',$instance->id)->where('parameter','publication-banner-image')->first()->value;
            }else{
                $instance->banner_image_url = DefaultTweakable::where('parameter','publication-banner-image')->first()->value;
            }
		}

		return View::make('landing', $data);
	}



}