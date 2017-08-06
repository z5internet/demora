<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\Image;

use Intervention\Image\Image;

use stdClass;

trait ImageControllerPrivate {

	private function retrieveImage($img) {

		try {

			return app('flysystem')->connection('images')->read($img);

		} catch (\Exception $e) {

			return '';

		}

	}

	private function getImageType($file) {

		$type = explode('.',$file);

		return $type[count($type)-1];

	}

	private function deleteImage($filename) {

		$this->deleteFile("0".$filename);

		foreach (config('react-user-framework.images.sizes') as $value) {

			$this->deleteFile($value.$filename);

		}

	}

	private function deleteFile($filename) {

		try {

			app('flysystem')->connection('images')->delete($filename);

		} catch (\League\Flysystem\FileNotFoundException $e) {

		}

	}

	private function getImageDimensions(Image $image) {

		$dims = new stdClass();

		$dims->width = $image->width();
		$dims->height = $image->height();

		return $dims;

	}

	private function imageObjectToString($obj) {

		return '-'.$obj->u.'-'.$obj->m.'.'.$obj->e;
	}

}
