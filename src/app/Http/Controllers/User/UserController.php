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

use z5internet\User\Models\passwordResets;

use stdClass;

class UserController extends Controller {

	public static function user($uid) {

		$user = self::getUser($uid);

		$u = new stdClass;

		$u->id = $user->id;
		$u->first_name = $user->first_name;
		$u->last_name = $user->last_name;
		$u->gender = $user->gender;
		$u->username = $user->username;

		$image = new stdClass;

		if ($user->image) {

			$image = json_decode($user->image);
			$image->p = config('react-user-framework.images.profile_image_public_path');

		}

		$u->image = $image;

		foreach (AdditionalUserData::getData($uid) as $k => $v) {

			$u->$k = $v;

		}

		return $u;

	}

	private static function cacheKey($uid) {

		return 'user-'.$uid;

	}

	public static function getUser($uid) {

		if (!config('react-user-framework.caching')) {

			return User::find($uid);

		}

		return app('cache')->rememberForever(self::cacheKey($uid), function() use ($uid) {

			return User::find($uid);

		});

	}

	private static function forgetFromCache($uid) {

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

		$credentials = $request->only('email', 'password');

		$user = self::getUserByEmail($credentials['email']);

		if (!$user) {

			return null;

		}

		if (!app('hash')->check($credentials['password'], $user->password)) {

			return null;

		}

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

	public static function returnLoginHeaders($request, $token, $content) {

		$response = response('', 200);

		$response->withCookie((new AuthenticationController($request))->cookie($token));

		$response->setContent(collect($content));

		return $response;

	}

	public static function joinFromSignupForm($data) {

		if (config('react-user-framework.website.disallow_public_signups')) {

			return [];

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

		$check = self::getUserByEmail($data['email'])->toArray();

		if (count($check)>0) {

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

		foreach ($userData as $k => $v) {

			$user->$k	=	$v;

		}

		$user->save();

		self::forgetFromCache($user->id);

		return;

	}

	public static function getIdFromUsername($username) {

		$u = User::where('username', $username)->first(['id']);

		if ($u) {

			return $u->id;

		}

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

}
