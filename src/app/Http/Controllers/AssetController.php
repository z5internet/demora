<?php namespace z5internet\ReactUserFramework\App\Http\Controllers;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use z5internet\ReactUserFramework\App\Http\Controllers\Admin\AdminController;

class AssetController extends Controller {

	public function getAsset($dir, $file) {

		$file = base_path('/assets/'.$dir.'/'.$file);

		if (file_exists($file)) {

			if ($dir == 'auth' && !app('auth')->check()) {

				$this->show404();

			}

			if ($dir == 'admin' && (!app('auth')->check() || !(new AdminController)->isUserAnAdmin())) {

				$this->show404();

			}

			$contents = file_get_contents($file);

			$fileType = \Defr\PhpMimeType\MimeType::get($file);

			return response($contents, 200)->header('Content-Type', $fileType);

		}

		$this->show404();

	}

	private function show404() {

		abort(404);

	}

}
