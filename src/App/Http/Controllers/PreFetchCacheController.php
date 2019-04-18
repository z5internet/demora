<?php namespace z5internet\ReactUserFramework\App\Http\Controllers;

class PreFetchCacheController extends Controller
{

	public function fetch(array $keys) {

		$keysToGet = [];

		foreach (array_unique($keys) as $k1) {

			if (!app('cache')->store('array')->get($k1)) {

				array_push($keysToGet, $k1);

			}

		}

		if (count($keysToGet) > 0) {

			return app('cache')->many($keysToGet);

		}

		return [];

	}

}