<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

 //Route::get('Operateurs', [OperateursApiController::class, 'index']);

Route::group([
    'middleware'=>'api',
    'namespace'=>'App\Http\Controllers',
    'prefix'=>'auth'
],
    function ($router) {
        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');
        Route::get('logout', 'AuthController@logout');
        Route::get('profile', 'AuthController@profile');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('save_user_info', 'AuthController@save_user_info');
     }
);

Route::group(
    [
        'middleware' => 'api',
        'namespace'  => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::get('Missions/missrap/{id}', 'MissionController@missrap');
        Route::get('Missions/missid/{id}', 'MissionController@missid');

        Route::resource('Missions', 'MissionController');
        Route::post('Users/getid', 'UsersController@getid');
        Route::post('Users/storeemp', 'UsersController@storeemp');
        Route::get('Users/getclient', 'UsersController@getclient');
        Route::get('Users/getemp', 'UsersController@getemp');
        Route::get('Users/LoadEmbs', 'UsersController@LoadEmbs');
        Route::get('Users/empjob', 'UsersController@empjob');
        Route::resource('Users', 'UsersController');
        Route::resource('/{mission}/Operation', 'OperationController');

        Route::get('/{operation}/datepointage/show_operateur', 'DatepointagesController@show_operateur');
        Route::get('/{operation}/datepointage/getrap', 'DatepointagesController@getrap');
        Route::get('/{operation}/datepointage/infoo', 'DatepointagesController@infoo');

        Route::resource('/{operation}/datepointage', 'DatepointagesController');
        Route::resource('/{datepointages}/detailpointage', 'DetailPointsController');
        Route::resource('/{mission}/sites', 'SiteController');
        Route::resource('/{operation}/article', 'ArticleController');
        Route::resource('{article}/refobs', 'RefobsController');
        Route::resource('{operation}/rapport/', 'RapportController');
        Route::get('/clientmission/getclientmission/{clientID}', 'ClientmissionController@getclientmission');
        Route::get('/clientmission/getmissionClient/{missid}', 'ClientmissionController@getmissionClient');
      
        Route::resource('/clientmission', 'ClientmissionController');
     }
);

