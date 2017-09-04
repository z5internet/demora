<?php

$this->route->group(['prefix' => 'mobile'], function () {

	/* AUTH */

	$this->route->post('/auth', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController@login');

	$this->route->get('/start', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController@start');

	$this->route->group(['middleware' => 'auth'], function () {

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

		$this->route->get('/uiNotifications', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesAuthController@uiNotifications');
		$this->route->put('/uiNotifications/{nid}', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesAuthController@markUiNotificationAsRead');

		$this->route->post('/image/checkChunk', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesAuthController@checkImageChunkAlreadytUploaded');

		$this->route->post('/image/uploadChunk', 'z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesAuthController@uploadImageChunk');

	});

});
