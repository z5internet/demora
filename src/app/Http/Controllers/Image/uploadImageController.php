<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\Image;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

class UploadImageController extends Controller
{

	public function uploadChunk($params) {

		$key = $this->getCacheKey($params);

		app('cache')->put($key, $params['chunk'], 5);

		$params['chunk'] = substr($params['chunk'], 0 , 20);

		return true;

	}

	public function checkIfUploadedChunkExists($params) {

		$key = $this->getCacheKey($params);

		return !!app('cache')->get($key);

	}

	public function getImageContentFromChunksInCache($params) {

		$image = '';

		$start = 0;

		for ($i=0 ; $i < $params['numberChunks']; $i++) {

			$end = $start + $params['chunkSize']-1;

			if ($end > $params['size']) {

				$end = $params['size']-1;

			}

			$key = $this->getCacheKey([

				'uid' => $params['uid'],
				'start' => $start,
				'end' => $end,
				'filename' => $params['filename'],

			]);

			$image .= app('cache')->get($key);

			$start = $end+1;

		}

		return $image;

	}

	private function getCacheKey($params) {

		return join('#-#', [$params['uid'], $params['start'], $params['end'], $params['filename']]);

	}

}
