<?php

	app(Illuminate\Contracts\Broadcasting\Factory::class)->channel('USER_{uid}', function ($user, $uid) {
		return $user->id == $uid;
	});