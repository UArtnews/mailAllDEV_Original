<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

    app_path().'/commands',
    app_path().'/controllers',
    app_path().'/models',
    app_path().'/database/seeds',
    app_path().'/Libraries',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
    Log::error($exception);
});

App::error(function(ModelNotFoundException $exception, $code)
{
    return Redirect::to('/')->withError('Sorry, but what you are looking for does not exist!');
});


/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
    return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';

//Make sure we have this function.  I'll stop doing this soon.
if(!function_exists('reindexArray')){
    function reindexArray($array, $index, $value)
    {

        $tempArr = array();

        foreach ($array as $item)
        {
            $tempArr[$item[$index]] = $item[$value];
        }

        return $tempArr;
    }
}

//Make sure we have this function.  I'll stop doing this soon.
if(!function_exists('uanet')){
    function uanet()
    {
        //Only SECURE routes can get uanetID's and be sure about it
        if(!Request::secure())
        {
            return false;
        }

        return Request::server('REMOTE_USER');
    }
}

//Make sure we have this function.  I'll stop doing this soon.
if(!function_exists('getInstanceName')){
    function getInstanceName($id = null)
    {
        if($id == null) {
            return Instance::where('name', strtolower(urldecode(Request::segment(2))))->firstOrFail()->name;
        }elseif($id == 0) {
            return 'GLOBAL';
        }else{
            return Instance::find($id)->name;
        }
    }
}

//Make sure we have this function.  I'll stop doing this soon.
if(!function_exists('excelToArray')){
    function excelToArray($fileName)
    {
        $sheetCount = 0;
        $rows = array();

        //Get sheet count
        Excel::filter('chunk')->load($fileName)->chunk(1000, function($results) use (&$sheetCount){
            foreach($results as $sheet){
                $sheetCount++;
            }
        });

        foreach(range(0, $sheetCount-1) as $sheetIndex){
            Excel::selectSheetsByIndex($sheetIndex)->filter('chunk')->load($fileName)->chunk(1000, function($results) use (&$rows){
                foreach($results as $row){
                    array_push($rows, $row->toArray());
                }
            });
        }

        return $rows;
    }
}

//Make sure we have this function.  I'll stop doing this soon.
if(!function_exists('excelOneRow')){
    function excelOneRow($fileName)
    {
        $sheetCount = 0;
        $row = array();

        //Get sheet count
        Excel::filter('chunk')->load($fileName)->chunk(1000, function($results) use (&$sheetCount){
            foreach($results as $sheet){
                $sheetCount++;
            }
        });

        foreach(range(0, $sheetCount-1) as $sheetIndex){
            Excel::selectSheetsByIndex($sheetIndex)->filter('chunk')->load($fileName)->limit(1)->chunk(1000, function($results) use (&$rows){
                foreach($results as $row){
                    array_push($rows, $row->toArray());
                }
            });
        }

        return $rows;
    }
}