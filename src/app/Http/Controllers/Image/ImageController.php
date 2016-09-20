<?php namespace darrenmerrett\ReactUserFramework\App\Http\Controllers\Image;

use darrenmerrett\ReactUserFramework\App\Http\Controllers\Controller;

use Auth;

use Storage;

use Image;

use darrenmerrett\ReactUserFramework\App\Http\Controllers\UserController;

class ImageController extends Controller
{

	use ImageControllerPrivate;

	public function get($img) {

		$type = $this->getImageType($img);

		$content = $this->retrieveImage($img);

		if (!$content) {

			return $this->create($img);

		}

		return response($content)->header('Content-Type', 'image/'.$type);

	}

	public function create($img) {

		$type = $this->getImageType($img);

		$image = explode('-',explode('.',$img)[0]);

		$size = $image[0];

		if (!in_array($image[0],config('DM.react-user-framework.images.sizes'))) {

			return;

		}

		$image[0] = '';

		$base_file = join('-',$image).'.'.$type;

		$content = $this->retrieveImage($base_file);

		if (!$content) {
			
			return;

		}

		$image = Image::make($content);

		$dims = $this->getImageDimensions($image);

		if ($dims->width > $dims->height) {

			$dims->height = intval($dims->height*$size/$dims->width);
			$dims->width = $size;

		}
		else
		{

			$dims->width = intval($dims->width*$size/$dims->height);
			$dims->height = $size;

		}

		$image->resize($dims->height, $dims->width);

		Storage::put($img,$image->encode($type));

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

		$image = Image::make($this->retrieveImage(Auth::user()->image));

		$type = preg_replace('/image\//','',$image->mime());

		return $this->put($image,$type,$crop,$prefix);

	}

	public function put($image,$type,$crop,$prefix) {

		$user = Auth::user();

		if (is_string($image)) {

			$image = Image::make($image);

		}

		$dims = $this->getImageDimensions($image);

		$image->crop(intval($crop['width']*$dims->width/100),intval($crop['height']*$dims->height/100),intval($crop['x']*$dims->width/100),intval($crop['y']*$dims->height/100));

		$image = $image->encode($type);

		$filename = '-'.$user->id.$prefix.'-'.md5($image).'.'.$type;

		$this->deleteImage($user->image);

		Storage::put($filename,$image);

		return [
			'u' => $user->id.$prefix,
			'm' => md5($image),
			'e' => $type,
		];

	}

}
