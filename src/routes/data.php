<?php

$this->route->group(['prefix' => 'data'], function () {

	/* AUTH */

	$this->route->post('/join', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController@join');

	$this->route->post('/auth', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController@login');

	$this->route->post('/password/email', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController@sendPasswordResetEmail');

	$this->route->post('/password/reset', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController@resetPassword');

	$this->route->post('/referer', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController@referer');

	$this->route->get('/auth/logout', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController@logout');

	$this->route->get('/start', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController@start');

	$this->route->post('/contactus', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController@contactUs');

	$this->route->group(['prefix' => 'setup'], function () {

		$this->route->get('/', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\setupRoutes@getIndex');
		$this->route->post('/checkusername', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\setupRoutes@postCheckusername');
		$this->route->post('/', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\setupRoutes@postIndex');

		$this->route->post('/setupExistingUser', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\setupRoutes@setupExistingUser');

	});

	$this->route->post('/log_error', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController@logError');

	$this->route->post('/push', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController@push');

});

$this->route->group(['middleware' => 'auth'], function () {

	$this->route->group(['prefix' => 'data'], function () {

		$this->route->post('/image/checkChunk', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController@checkImageSlice');

		$this->route->post('/image/uploadChunk', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController@uploadImageSlice');

		$this->route->get('/referals', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController@referals');

		$this->route->get('/uiNotifications', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController@uiNotifications');
		$this->route->put('/uiNotifications/{nid}', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController@markUiNotificationAsRead');

		$this->route->group(['prefix' => 'settings'], function () {

			$this->route->put('/', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\settingsRoutes@putIndex');
			$this->route->post('/uploadprofileimage', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\settingsRoutes@postUploadprofileimage');
			$this->route->post('/addTeam', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\settingsRoutes@addTeam');
			$this->route->put('/editTeamName', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\settingsRoutes@editTeamName');
			$this->route->post('/addTeamMember', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\settingsRoutes@addTeamMember');
			$this->route->post('/getTeamMembers', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\settingsRoutes@getTeamMembers');

			$this->route->delete('/deletedTeam', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\settingsRoutes@deleteTeam');
			$this->route->delete('/deletedActiveUser', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\settingsRoutes@deleteActiveUser');
			$this->route->delete('/deletedInvitedUser', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\settingsRoutes@deleteInvitedUser');


		});

		$this->route->group(['prefix' => 'admin'], function () {

			$this->route->group(['prefix' => 'products'], function () {

				$this->route->get('/', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\adminRoutes@getProducts');
				$this->route->post('/', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\adminRoutes@createProduct');
				$this->route->put('/', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\adminRoutes@editProduct');

			});

		});

	});

});

$this->route->group(['prefix' => 'data/payment'], function () {

	$this->route->post('/hook/stripe', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\payRoutes@processStripeEvent');

	$this->route->group(['middleware' => 'auth'], function () {

		$this->route->post('/addProduct/{productId}', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\payRoutes@addProductToTeam');

		$this->route->group(['prefix' => 'stripe'], function () {

			$this->route->post('/token', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\payRoutes@saveStripeToken');

		});

	});

});

