<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\Image;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use z5internet\ReactUserFramework\App\Http\Controllers\UserController;

class ImageController extends Controller
{

	use ImageControllerPrivate;

	public function get($img) {

		$type = $this->getImageType($img);

		$image = explode('-', $img);

		if (!app('auth')->check() && !preg_match('/p$/', explode('-',$img)[1])) {

			abort(404);

		}

		$content = $this->retrieveImage($img);

		if (!$content) {

			$content = $this->create($img);

			if (!$content) {

				abort(404);

			}

			return $content;

		}

		$response = response($content)->header('Content-Type', 'image/'.$type);

		$response = $response->header('Cache-Control', 'max-age=172801');

		return $response;

	}

	public function create($img) {

		$type = $this->getImageType($img);

		$image = explode('-',explode('.',$img)[0]);

		$size = $image[0];

	    $stop = true;

	    foreach (config('react-user-framework.images.sizes') as $ti) {

	        if ((string)$ti === (string)$size || (string)$size == "0") {

	            $stop = false;

	        }

	    }

	    if ($stop) {

	        return;

	    }

		$image[0] = '0';

		$base_file = join('-',$image).'.'.$type;

		$content = $this->retrieveImage($base_file);

		if (!$content) {

			return;

		}

		$image = app('image')->make($content);

		$dims = $this->getImageDimensions($image);

		$dimension = null;

		if (preg_match('/[0-9]([a-z])$/', $size, $match)) {

			$dimension = $match[1];

		}

		$size = (int)$size;

		$width = null;
		$height = null;

		switch ($dimension) {

			case 'w':

				$width = $size;

				break;

			case 'h':

				$height = $size;

				break;

			default:

				if ($dims->width > $dims->height) {

					$width = $size;

				}
				else
				{

					$height = $size;

				}

		}

		if ($size > 0) {

		    $image->resize($width, $height, function ($constraint) {

		        $constraint->aspectRatio();
		        $constraint->upsize();

		    });

		}

		$this->putIntoStorage($img, $image->encode($type)->__toString());

		return $this->get($img);

	}

	public function putBase64Image($imageData,$crop,$prefix='') {

		$imageData = explode(',',$imageData);

		$image = base64_decode($imageData[1]);

		preg_match("/^data:image\/(\w+);base64$/", $imageData[0],$type);

		$type = $type[1];

		return $this->put($image,$type,$crop,$prefix);

	}

	public function cropImage($crop,$prefix='') {

		$image = app('image')->make($this->retrieveImage(app('auth')->user()->image));

		$type = preg_replace('/image\//','',$image->mime());

		return $this->put($image,$type,$crop,$prefix);

	}

	public function put($image,$type,$crop,$prefix) {

		$user = app('auth')->user();

		if (is_string($image)) {

			$image = app('image')->make($image);

		}

		$dims = $this->getImageDimensions($image);

		$image->crop(intval($crop['width']*$dims->width/100),intval($crop['height']*$dims->height/100),intval($crop['x']*$dims->width/100),intval($crop['y']*$dims->height/100));

		$image = $image->encode($type);

		$filename = '0-'.$user->id.$prefix.'-'.md5($image).'.'.$type;

		$currentImage = json_decode($user->image);

		if (isset($currentImage->u)) {

			$this->deleteImage(
				$this->imageObjectToString($currentImage)
			);

		}

		$this->putIntoStorage($filename, $image->__toString());

		return [
			'u' => $user->id.$prefix,
			'm' => md5($image),
			'e' => $type,
			'w' => $dims->width,
			'h' => $dims->height,
		];

	}

	public function putIntoStorage($filename, $image) {

		app('flysystem')->connection('images')->put($filename, $image);

	}

}
