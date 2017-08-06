<?php namespace z5internet\ReactUserFramework\App\Http\Controllers;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

class ReferralsController extends Controller
{

	public function show() {

		$uid = app('auth')->id();

		$refs = User::where('referrer', $uid)->paginate(10);

		$data = [];

		foreach ($refs as $key => $tr) {

			$o = [
				'date' => $tr['created_at']->toDateTimeString(),
				'first_name' => $tr['first_name'],
				'id' => $tr['id'],
				'image' => $tr['image'],

			];

			$refs[$key] = $o;

		}

		return $refs;

	}

}
