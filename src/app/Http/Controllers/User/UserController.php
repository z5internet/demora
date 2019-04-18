<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\User;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use z5internet\ReactUserFramework\App\Http\Controllers\StartController;

use z5internet\ReactUserFramework\App\Http\Controllers\SetupController;

use z5internet\ReactUserFramework\App\Http\Controllers\Auth\AuthenticationController;

use z5internet\ReactUserFramework\App\Http\Controllers\User\AdditionalUserData;

use Redirect;

use z5internet\ReactUserFramework\App\Joined;

use App\User;

use tokenAuth;

use Illuminate\Http\Request;

use stdClass;

use Egulias\EmailValidator\EmailValidator;

use Egulias\EmailValidator\Validation\RFCValidation;

use z5internet\ReactUserFramework\App\Http\Controllers\User\Login\ThrottlesLogins;

use z5internet\ReactUserFramework\App\Events\LoginFailedAttempt;

use z5internet\ReactUserFramework\App\Events\LoginSuccessful;

use z5internet\ReactUserFramework\App\Http\Controllers\User\Login\TwoFactorAuth;

class UserController extends Controller {

	public static function user($uid) {

		$userC = self::getUserFromCache($uid);

		$user = $userC['user'];

		if (!$user) {

			return;

		}

		$u = new stdClass;

		$u->id = $user->id;
		$u->first_name = $user->first_name;
		$u->last_name = $user->last_name;
		$u->gender = $user->gender;
		$u->username = $user->username;

		if (isset($user->online) && !(($user->online + 300) < time())) {

			$u->o = true;

		}

		$image = new stdClass;

		if ($user->image) {

			$image = json_decode($user->image);
			$image->p = config('react-user-framework.images.profile_image_public_path');

		}

		$u->image = $image;

		foreach ($userC['additional'] as $k => $v) {

			$u->$k = $v;

		}

		return $u;

	}

	private static function cacheKey($uid) {

		$uid = explode('-', $uid)[0];

		return 'user-'.$uid;

	}

	public static function getUser($uid) {

		return self::getUserFromCache($uid)['user'];

	}

	public static function getUserFromCache($uid) {

		if (!config('react-user-framework.caching')) {

			return self::getUserFromCacheDB($uid);

		}

		return app('cache')->rememberForever(self::cacheKey($uid), function() use ($uid) {

			return self::getUserFromCacheDB($uid);

		});

	}

	public static function getUserFromCacheDB($uid) {

		return [

			'user' => User::find($uid),
			'additional' => AdditionalUserData::getData($uid),

		];

	}

	public static function forgetFromCache($uid) {

		if (!config('react-user-framework.caching')) {

			return;

		}

		app('cache')->forget(self::cacheKey($uid));

	}

	public static function getUserByEmail($email) {

		$uid = User::where('email', $email)->first();

		if (!$uid) {

			return null;

		}

		return self::getUser($uid->id);

	}

	public static function getUserByUsername($username) {

		$uid = self::getIdFromUsername($username);

		if ($uid) {

			return self::getUser($uid);

		}

	}

	public static function login($request) {

		$ThrottlesLogins = new ThrottlesLogins;

		$credentials = $request->only('email', 'password');

		if ($ThrottlesLogins->hasTooManyLoginAttempts($request)) {

			$ThrottlesLogins->fireLockoutEvent($request);

			return 'You have made too many login attempts. Please wait before trying again.';

		}

		$user = self::getUserByEmail($credentials['email']);

		if ($user && app('hash')->check($credentials['password'], $user->password)) {

			if ($twofa = (new TwoFactorAuth)->send2FAIfRequired($user)) {

				return ['twofa' => $twofa];

			}

			return self::loginSuccessful($user);

		}

		$ThrottlesLogins->incrementLoginAttempts($request);

		$checkAttemptsLeft = $ThrottlesLogins->checkAttemptsLeft($request)+1;

		event(new LoginFailedAttempt($request, $checkAttemptsLeft));

		if ($checkAttemptsLeft < 3) {

			return 'Incorrect Login Details. You only have '.$checkAttemptsLeft.' attempts left';

		}

		return 'Incorrect Login Details';

	}

	public static function Confirm2FA($request) {

		$credentials = $request->only('email', 'password', 'code');

		$user = self::getUserByEmail($credentials['email']);

		if ($user && app('hash')->check($credentials['password'], $user->password)) {

			if (!(new TwoFactorAuth)->checkCodeIsValid($user->id, $credentials['code'])) {

				return false;

			}

			return self::loginSuccessful($user);

		}

	}

	private static function loginSuccessful($user) {

		event(new LoginSuccessful($user));

		(new ThrottlesLogins)->clearLoginAttempts(app('request'));

		return self::doLoginFromUser($user);

	}

	public static function loginUsingId($uid) {

		$user = self::getUser($uid);

		return self::doLoginFromUser($user);

	}

	private static function doLoginFromUser($user) {

		if (!$user) {

			return null;

		}

		app('auth')->setUser($user);

		$user->token = app('tymon.jwt.auth')->fromUser($user);

		return $user;

	}

	public static function returnLoginHeaders($request, $token, $content, $twofa = false) {

		$response = response('', 200);

		$response->withCookie((new AuthenticationController($request))->cookie($token));

		if ($twofa) {

			$response->withCookie((new TwoFactorAuth)->createCookie($content['data']['user']->id));

		}

		$response->setContent(collect($content));

		return $response;

	}

	public static function joinFromSignupForm($data) {

		if (config('react-user-framework.website.disallow_public_signups')) {

			return [];

		}

		if (!(new EmailValidator)->isValid($data['email'], new RFCValidation())) {

			return [
				'errors' => [
					[
						'message' => 'The email address you entered is not valid, please check the email address you entered.',
					],
				],
			];

		}

		$cookie	=	self::decodeReferralCookieAndCheckReferrer($data['ref']);

		if (!$cookie["referrer"] && config('react-user-framework.joinMustHaveReferral')) {

			$message	=	'We only accept referrals from other members. If you contact the member that told you about us, they can send you an invitiation';

			return [
				'errors' => [
					[
						'message' => $message,
					],
				],
			];

		}

		$check = self::getUserByEmail($data['email']);

		if ($check) {

			$message	=	'Your email address is already registered. If you have forgotten your password you can click on forgotten password to receive a new one.';

			return [
				'errors' =>	[
					[
						'message' => $message,
					],
				],
			];

		}

		return self::join($data, 'vendor.ruf.email.registeredEmail');

	}

	public static function join($data, $emailTemplate) {

		$check = Joined::where('email', $data['email']);

		if (isset($data['team'])) {

			$check = $check->where('team', $data['team']);

		}

		$check = $check->get(['id','code'])->toArray();

		$data['first_name']	= ucfirst($data['first_name']);

		if (count($check)>0) {

			$check=$check[0];

			$id		=	$check['id'];

			$code	=	$check['code'];

			$j = Joined::where('email', $data['email']);

			$j->update(['first_name' => $data['first_name']]);

		}
		else
		{

			$code	=	md5($data['email'].microtime());

			$jd = new Joined;

			$jd->first_name = ucfirst($data['first_name']);
			$jd->email = $data['email'];
			$jd->code = $code;
			$jd->created_at = app('db')->raw('now()');

			if (isset($data['ref'])) {

				$jd->ref =	$data['ref'];

			}

			if (isset($data['team'])) {

				$jd->team = $data['team'];

			}

			$jd->teamRole = isset($data['teamRole'])?$data['teamRole']:255;

			$jd->save();

			$id		=	$jd->id;

		}

		$data=array(

			"first_name"	=>	$data['first_name'],
			"email"			=>	$data['email'],
			"link"			=>	config('app.url')."/setup?id=".$id."&code=".$code

			);

		app('mailer')->send($emailTemplate, $data, function($message) Use ($data) {

			$message->to($data['email'], $data['first_name'])->subject('Verify your '.config('app.name').' email address.');

		});

		return ['joined'	=>	1];

	}

	public static function addUser($user) {

		$data	=	[];

		$data["first_name"]	=	$user["first_name"];
		$data["last_name"]	=	$user["last_name"];
		$data["username"]	=	$user["username"];
		$data["email"]		=	$user["email"];
		$data["gender"]		=	$user["gender"];
		$data["password"]	=	app('hash')->make($user["password"]);
		$data["image"]		=	'{}';

		if ($user["referrer"])	{

			$data["referrer"]		=	$user["referrer"];

		}

		if ($user["referred_url"])	{

			$data["referred_url"]	=	$user["referred_url"];

		}

		$user = new User;

		foreach($data as $key => $value) {
			$user->$key = $value;
		}

		$user->save();

		self::forgetFromCache($user->id);

		return $user->id;

	}

	public static function updateUser($userData, $id) {

		$user = self::getUser($id);

		unset($user->online);

		unset($user->token);

		foreach ($userData as $k => $v) {

			$user->$k	=	$v;

		}

		$user->save();

		self::forgetFromCache($user->id);

		return;

	}

	private static $idsOfUsernames = [];

	public static function getIdFromUsername($username) {

		if ($uid = array_get(self::$idsOfUsernames, $username)) {

			return $uid;

		}

		$u = User::where('username', $username)->first(['id']);

		if ($u) {

			self::$idsOfUsernames[$username] = $u->id;

			return $u->id;

		}

		self::$idsOfUsernames[$username] = null;

	}

	public static function formatUserId($uid) {

		if (is_numeric($uid)) {

			$uid	.=	"-u";

		}

		return $uid;

	}

	public static function decodeReferralCookieAndCheckReferrer($cookie) {

		$out	=	[

			'referrer' => '',
			'referred_url' => '',

		];

		$cookie	=	json_decode($cookie,1);

		if (is_array($cookie)) {

			$cookie	=	$cookie	+ ['u' => '', 'r' => ''];

			if ($cookie['u']) {

				$rid = self::getIdFromUsername($cookie['u']);

				if ($rid) {

					$out['referrer'] = $rid;

				}

			}

			if ($cookie['r']) {

				$out['referred_url'] = $cookie['r'];

			}

		}

		return $out;

	}

	public static function setOnline($uid) {

		$u = self::getUserFromCache($uid);

		$u['user']->online = time();

		app('cache')->put(self::cacheKey($uid), $u, 3600);

	}

	public static function getManyCacheKeysForUsers(array $uids) {

		$out = [];

		foreach ($uids as $uid) {

			array_push($out, self::cacheKey($uid));


		}

		return $out;

	}

}
