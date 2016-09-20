<?php namespace darrenmerrett\ReactUserFramework\App\Http\Controllers\Image;

use Storage;

use Intervention\Image\Image;

use stdClass;

trait ImageControllerPrivate {

	private function retrieveImage($img) {

		try {

			return Storage::get($img);

		} catch (\Exception $e) {

			return '';

		}

	}

	private function getImageType($file) {

		$type = explode('.',$file);

		return $type[count($type)-1];

	}

	private function deleteImage($filename) {

		$files = [$filename];

		foreach (config('DM.react-user-framework.images.sizes') as $value) {

			array_push($files,$value.$filename);

		}

		Storage::delete($files);

	}

	private function getImageDimensions(Image $image) {

		$dims = new stdClass();

		$dims->width = $image->width();
		$dims->height = $image->height();

		return $dims;

	}

}
