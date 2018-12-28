<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\Routing;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use z5internet\ReactUserFramework\App\Http\Controllers\User\UserController;

use z5internet\ReactUserFramework\App\Http\Controllers\ContactController;

use z5internet\ReactUserFramework\App\Http\Controllers\StartController;

use z5internet\ReactUserFramework\App\Http\Controllers\Image\ImageController;

use z5internet\ReactUserFramework\App\Http\Controllers\ErrorLogController;

use z5internet\ReactUserFramework\App\Http\Controllers\AssetController;

use z5internet\ReactUserFramework\App\Http\Controllers\uiNotificationsController;

use Illuminate\Http\Request;

use z5internet\ReactUserFramework\App\Http\Controllers\User\ForgotPasswordController;

use z5internet\ReactUserFramework\App\Http\Controllers\Image\UploadImageController;

use z5internet\ReactUserFramework\App\Http\Controllers\Auth\AuthenticationController;

use Symfony\Component\HttpFoundation\Cookie;

use z5internet\ReactUserFramework\App\Events;

use Carbon\Carbon;

class routesController extends Controller
{

	public function __construct(Request $request) {

		$this->request = $request;

	}

	public function start() {

		if ($uid = app('auth')->id()) {

			Events::insert(['uid' => $uid, 'event' => 1, 'created_at' => new Carbon]);

		}

		return ['data' => (new StartController)->show()];

	}

	public function login() {

		$user = UserController::login($this->request);

		if (!$user) {

			return ['errors' => [['message' => 'Incorrect Login Details']]];

		}

		$st = (new StartController)->show($user->id)['user'];

		return UserController::returnLoginHeaders($this->request, $user->token, ['data' => ['user' => $st, 'token' => $user->token]]);

	}

	public function join() {

        $data = $this->request->only('first_name', 'email');

		$data["ref"] = $this->request->cookie('sou');

		$data['email'] = strtolower($data['email']);

		$j = UserController::joinFromSignupForm($data);

		if (isset($j['errors'])) {

			return ['errors' => $j['errors'], 'data' => []];

		}

		return ['data' => $j];

	}

	public function referer($refer, $redirectTo = '/', $referral_url = null) {

		$sou = [
			'r' => $referral_url?$referral_url:array_get($_SERVER, 'HTTP_REFERER'),
			'u' => $refer,
		];

		$cookieSettings = config('react-user-framework.website.cookie');

		$domain = array_get($cookieSettings, 'domain')?$cookieSettings['domain']:$this->request->getHttpHost();

		$existing_cookie = json_decode(array_get($_COOKIE, 'sou'), true);

		if (is_array($existing_cookie)) {

			if ($sou['u'] && $sou['u'] == array_get($existing_cookie, 'u')) {

				if ($sou['r'] && preg_match('~^'.config('app.url').'~', $sou['r'])) {

					$sou['r'] == $existing_cookie['u'];

				}
				else
				{

					$sou['r'] = $existing_cookie['r'];

				}

			}

		}

		return redirect($redirectTo)->withCookie(new Cookie(
			'sou',
			json_encode($sou),
		    time()+(60*60*24*365),
		    '/'
		));

	}

	public function logoutWithRedirect() {

        return redirect('/login')->cookie((new AuthenticationController($this->request))->cookie(false));

	}

	public function staticData() {

		return UserController::showStaticData();

	}

	public function contactUs() {

		$data = $this->request->only('name','email','message');

		$data['email'] = strtolower($data['email']);

		return (new ContactController)->contactUs($data);

	}

	public function getImage($img) {

		return (new ImageController)->get($img);

	}

	public function createImage($img) {

		return (new ImageController)->create($img);

	}

	public function logError() {

		$data = $this->request->only('stacktrace', 'url', 'type')+['stacktrace' => '', 'url' => '', 'type' => ''];

		$data['uid'] = 0;

		if ($uid = app('auth')->id()) {

			$data['uid'] = $uid;

		}

		(new ErrorLogController)->LogError($data);

		return ['data' => []];

	}

	public function uiNotifications(Request $request) {

		$uid = app('auth')->id();

		$endCursor = $request->input('endCursor');

		return [
			'data' => (new uiNotificationsController)->showNotifications($uid, $endCursor),
		];

	}

	public function markUiNotificationAsRead($nid) {

		$uid = app('auth')->id();

		return [
			'data' => [
				'uiNotifications' => (new uiNotificationsController)->markUiNotificationAsRead($nid, $uid),
			],
		];

	}

	public function getAsset($dir, $file) {

		return (new AssetController)->getAsset($dir, $file);

	}

	public function sendPasswordResetEmail(Request $request) {

		(new ForgotPasswordController)->sendResetLinkEmail(strtolower($request->input('email')));
		return ['data' => []];

	}

/**
	public function sendResetLinkEmail() {

		$data = $this->request->only('email', );

		return UserController::sendResetLinkEmail($data);

	}
**/

	public function resetPassword(Request $request) {

		$data = $request->only(['email', 'token', 'password', 'password_confirmation']);

		$data['email'] = strtolower($data['email']);

		$result = (new ForgotPasswordController)->reset($data);

		if ($result == 'reset') {

			return ['data' => []];

		}

		$error = '';

		switch($result) {

			case 'invalid_password':

				$error = 'There was a problem changing your password. Check your password matches.';

				break;

			case 'invalid_token_email':

				$error = 'There was a problem changing your password. Please check your email address is typed correctly. If you are still having problems try requesting another password reset email.';

				break;

			default:

				$error = 'There was a problem changing your password.';
		}

		return ['errors' => [['message' => $error]], 'data' => []];

	}

	public function uploadImageChunk(Request $request) {

		return ['data' => [

			'uploaded' => (new UploadImageController)->uploadChunk($this->getImageSliceParams($request)),
			'feed' => [],

		]];

	}

	public function checkImageChunkAlreadytUploaded(Request $request) {

		return ['data' => [

			'uploaded' => (new UploadImageController)->checkIfUploadedChunkExists($this->getImageSliceParams($request)),

		]];

	}

	private function getImageSliceParams(Request $request) {

		$filename = $request->input('filename');
		$chunk = $request->input('chunk');
		$start = $request->input('start');
		$end = $request->input('end');

		return [
			'filename' => $filename,
			'chunk' => $chunk,
			'start' => $start,
			'end' => $end,
			'uid' => app('auth')->id(),
		];

	}

}
