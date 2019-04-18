<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\User\Login;

use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;

use z5internet\ReactUserFramework\App\TwoFACodes;

use Carbon\Carbon;

use Symfony\Component\HttpFoundation\Cookie;

class TwoFactorAuth {

	private $cookieName = 'rufT2FA';

	private $con2fa = null;

	public function __construct() {

		$this->con2fa = config('react-user-framework.auth.2fa');

		$this->request = app('request');

	}

	public function send2FAIfRequired($user) {

		if (!$this->is2FARequired($user->id)) {

			return false;

		}

		$code = RAND(1000,9999);

		TwoFACodes::insert([

			'uid' => $user->id,
			'code' => $code,
			'created_at' => app('db')->raw('now()'),

		]);

		switch($this->con2fa) {

			case 'email':

				$data = [

					'email' => $user->email,
					'first_name' => $user->first_name,
					'code' => $code,

				];

				app('mailer')->send('TwoFactorAuthCode', $data, function($message) Use ($data) {

					$message->to($data['email'], $data['first_name'])->subject('Your '.config('app.name').' login security code.');

				});

				break;

		}

		return $this->con2fa;

	}

	public function checkCodeIsValid($uid, $code) {

		$t = (new Carbon)->subMinutes(10);

		$a = !!TwoFACodes::where('uid', $uid)
			->where('code', $code)
			->where('created_at', '>', $t)
			->first();

		if ($a) {

			TwoFACodes::where('uid', $uid)->delete();

			TwoFACodes::where('created_at', '<', $t)->delete();

		}

		return !!$a;

	}

	private function is2FARequired($uid) {

		if (!$this->con2fa) {

			return false;

		}

		if (!$this->isValidCookie($uid)) {

			return true;

		}

		return false;

	}

	private function isValidCookie($uid) {

		$c = $this->get2FACookie();

		if (!$c) {

			return false;

		}

		if ($c == $uid) {

			return true;

		}

		return false;

	}

	private function get2FACookie() {

		$value = app('request')->cookie($this->cookieName);

		try {

			return app('encrypter')->decrypt($value);

		} catch (\Exception $e) {}

		return null;

	}

	/* COOKIE */

	private function getCookieSettings() {

		return config('react-user-framework.website.cookie');

	}

	public function createCookie($value) {

		$cookieSettings = $this->getCookieSettings();

		return new Cookie(
			$this->cookieName,
			$value?app('encrypter')->encrypt($value):'',
			time() + (10 * 365 * 24 * 60 * 60),
			'/data/auth',
			$this->getDomain(),
			$cookieSettings['secure'],
			true
		);

	}

	private function getDomain() {

		$cookieSettings = $this->getCookieSettings();

		return array_get($cookieSettings, 'domain')?$cookieSettings['domain']:$this->request->getHttpHost();

	}

}