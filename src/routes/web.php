<?php

$routes = [

	'/',
	'/admin',
	'/contact',
	'/getStarted',
	'/getStarted/{p}',
	'/home',
	'/login',
	'/privacy',
	'/settings',
	'/settings/{p}',
	'/setup',
	'/setupExistingUser',
	'/signup',
	'/terms',

];

$this->route->get('/logout', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController@logoutWithRedirect');

//	$this->route->post('/broadcasting/createToken', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\broadcastRoutes@createToken');

$this->route->post('/broadcasting/auth', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\broadcastRoutes@authenticate');

foreach ($routes as $tr) {

	$this->route->get($tr,function() {

		return file_get_contents(base_path('public/assets/index.html'));

	});

}

$this->route->get('/favicon.ico',function(){});

if ($this->app instanceof \Laravel\Lumen\Application) {

	$this->route->get('/{img:[^\/]*\-[^\/]*\-[^\/]*\.[a-z]{3,4}}', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\imagesRoutes@showImage');

}
else
{

	Route::get('/{img}', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\imagesRoutes@showImage')->where('img','[^\/]*\-[^\/]*\-[^\/]*\.[a-z]{3,4}');

	Route::get('{refer}', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController@referer')->where('refer', '^[^/\.]*$');

}

$this->route->get('/assets/{dir}/{file}', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController@getAsset');

$this->route->get('/password/reset', ['as' => 'password.request', function() {

	return file_get_contents(base_path('public/assets/index.html'));

}]);

$this->route->get('/password/reset/{token}', ['as' => 'password.reset', function() {

	return file_get_contents(base_path('public/assets/index.html'));

}]);


