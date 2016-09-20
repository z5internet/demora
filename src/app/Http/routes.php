<?php

Route::group(['prefix' => 'data'], function () {
	
	Route::get('/start', 'darrenmerrett\ReactUserFramework\App\Http\Controllers\Routing\routesController@start');

	Route::post('/auth', 'darrenmerrett\ReactUserFramework\App\Http\Controllers\Routing\routesController@login');

	Route::post('/join', 'darrenmerrett\ReactUserFramework\App\Http\Controllers\Routing\routesController@join');
	
	Route::get('/auth/logout', 'darrenmerrett\ReactUserFramework\App\Http\Controllers\Routing\routesController@logout');

	Route::post('/contactus', 'darrenmerrett\ReactUserFramework\App\Http\Controllers\Routing\routesController@contactUs');

	Route::group(['prefix' => 'setup'], function () {

		Route::get('/', 'darrenmerrett\ReactUserFramework\App\Http\Controllers\Routing\setupRoutes@getIndex');
		Route::post('/checkusername', 'darrenmerrett\ReactUserFramework\App\Http\Controllers\Routing\setupRoutes@postCheckusername');
		Route::post('/', 'darrenmerrett\ReactUserFramework\App\Http\Controllers\Routing\setupRoutes@postIndex');

	});

#	Route::post('/auth/sendResetLinkEmail','darrenmerrett\ReactUserFramework\App\Http\Controllers\Routing\routesController@sendResetLinkEmail');

#	Route::post('/auth/resetPassword', 'darrenmerrett\ReactUserFramework\App\Http\Controllers\Routing\routesController@resetPassword');

	
});

Route::group(['middleware' => 'auth'], function () {

	Route::get('/{img}', 'darrenmerrett\ReactUserFramework\App\Http\Controllers\Routing\routesController@createImage')->where('path','[^\/]*\-[^\/]*\-[^\/]*\.jpg');

	Route::get('/i/{img}', 'darrenmerrett\ReactUserFramework\App\Http\Controllers\Routing\routesController@getImage');

	Route::group(['prefix' => 'data'], function () {

		Route::group(['prefix' => 'settings'], function () {

			Route::put('/',					'darrenmerrett\ReactUserFramework\App\Http\Controllers\Routing\settingsRoutes@putIndex');
			Route::put('/uploadprofileimage',					'darrenmerrett\ReactUserFramework\App\Http\Controllers\Routing\settingsRoutes@postUploadprofileimage');

		});

	});

});