<?php
/* CUSTOM AUTH FILTER
/*
/*
/**/
Route::filter('simpleAuth', function(){
    $name = 'urlSession';

    //Check if cookie is set
    if(Cookie::has($name)){
        $cookie = Cookie::get($name);
    }else{
        $cookie = '';
    }

    $date = date('Y-m-d');
    $value = md5('urlAdminS3kR3T'.$date);


    $uri = Request::path();

    if($cookie == $value){
        //Let them in
    }else{
        //check to see if they've already whiffed the login page
        //Log them in, or not
        return Redirect::to('/admin/login?redirect='.$uri);
    }
});

/* CUSTOM SSL Filter
/*
/*
/**/
Route::filter('force.ssl', function()
{
    if( ! Request::secure())
    {
        return Redirect::secure(Request::path());
    }

});

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::guest('/');
});

Route::filter('instanceAuth', function() {
    $user = User::where('uanet', uanet())->first();

    if(count($user) <= 0){
        dd('count');
        return Redirect::guest('/');
    }else{
        Auth::login(User::find($user->id));
    }

    if($user->isSuperAdmin()){

    }elseif($user->hasPermission(getInstanceName(), 'edit')){

    }
        
    if (Auth::guest()) return Redirect::guest('/');
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});