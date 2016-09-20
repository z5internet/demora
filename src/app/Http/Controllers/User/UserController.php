<?php namespace darrenmerrett\ReactUserFramework\App\Http\Controllers\User;

use darrenmerrett\ReactUserFramework\App\Http\Controllers\Controller;

use darrenmerrett\ReactUserFramework\App\Http\Controllers\StartController;

use Redirect;

use Auth;

use darrenmerrett\ReactUserFramework\App\Joined;

use Mail;

use User;

use Hash;

use tokenAuth;

use Illuminate\Http\Request;

use Validator;

use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use darrenmerrett\User\Models\passwordResets;

use Illuminate\Cache\RateLimiter;

class UserController extends Controller {
	
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;
	
	protected $maxLoginAttempts = 5;
	
	protected $lockoutTime = 60;

	protected $isReturnJson;

	public function user($uid) {
	
		$user 	=	User::find($uid);

		return [

			'id' => $user->id,
			'first_name' => $user->first_name,
			'last_name' => $user->last_name,
			'gender' => $user->gender,
			'image' => ($user->image?array_merge(json_decode($user->image,1),[
				'p' => config('DM.react-user-framework.images.profile_image_public_path'),
			]):[]),
			'username' => $user->username,

		];

	}

	public function login(Request $request) {

		$this->isReturnJson	= true;

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        if (Auth::guard($this->getGuard())->attempt($credentials, $request->has('remember'))) {

			return $this->handleUserWasAuthenticated($request, $throttles);
			
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles && ! $lockedOut) {
            $this->incrementLoginAttempts($request);
        }

		return ['data'=>[]];
				
	}
	
    protected function sendLockoutResponse(Request $request)
    {
		
        $seconds = app(RateLimiter::class)->availableIn(
            $this->getThrottleKey($request)
        );
		
		return [
			
			"error"	=>	$this->getLockoutErrorMessage($seconds)
			
		];

    }
	
	protected function authenticated($request,$user) {

		return (new StartController)->show($user->id);

	}

	public function logout() {
		
		Auth::guard($this->getGuard())->logout();
				
		return ['data' => []];
			
	}

	public function join($data) {
		
		$check	=	User::where("email","=",$data["email"])->get(['id'])->toArray();
		
		if (count($check)>0) {
			
			$message	=	"Your email address is already registered. If you have forgotten your password you can click on forgotten password to receive a new one.";
		
			return ["data" => ["error"	=>	$message]];	
			
		}
		
		$cookie	=	$this->decodeReferralCookieAndCheckReferrer($data["ref"]);
		
		if (!$cookie["referrer"] && config('react-user-framework.joinMustHaveReferral')) {
			
			$message	=	"We only accept referrals from other members. If you contact the member that told you about us, they can send you an invitiation";
				
			return ["error"	=>	$message];
			
		}
		
		$check				=	Joined::where("email","=",$data["email"])->get(['id','code'])->toArray();
		
		$data["first_name"]	=	ucfirst($data["first_name"]);
		
		if (count($check)>0) {

			$check=$check[0];
			
			$id		=	$check["id"];
		
			$code	=	$check["code"];
			
			$j = Joined::where("email","=",$data["email"]);

			$j->update(['first_name' => $data["first_name"]]);
							
		}
		else
		{
		
			$code	=	md5($data["email"].microtime());

			$jd = new Joined;
		
			$jd->first_name = ucfirst($data["first_name"]);
			$jd->email = $data["email"];
			$jd->code = $code;
			
			if ($data["ref"]) {

				$jd->ref =	$data["ref"];

			}

			$jd->save();

			$id		=	$jd->id;
		
		}
		
		$data=array(
			
			"first_name"	=>	$data['first_name'],
			"email"			=>	$data['email'],
			"link"			=>	'http://'.config('DM.react-user-framework.website.domain')."/setup?id=".$id."&code=".$code
			
		);
		
		Mail::send('vendor.ruf.email.registeredEmail', $data, function($message) Use ($data) {
			
			$message->to($data["email"], $data['first_name'])->subject('Verify your '.config('DM.react-user-framework.website.website_name').' email address.');
			
		});
				
		return ["data" => ["joined"	=>	1]];
				
	}
	
	public function addUser($user) {
		
		$data	=	[];
		
		$data["first_name"]	=	$user["first_name"];
		$data["last_name"]	=	$user["last_name"];		
		$data["username"]	=	$user["username"];		
		$data["email"]		=	$user["email"];		
		$data["gender"]		=	$user["gender"];		
		$data["password"]	=	Hash::make($user["password"]);
		
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
		
		return $user->id;
		
	}
	
	public function updateUser($userData,$id) {
		
		$user = User::find($id);
		
		foreach ($userData as $k => $v) {
			
			$user->$k	=	$v;
			
		}

		$user->save();

		return;
				
	}
	
	public function returnUser($obj) {

		return [
			
			"n"	=>	[
				
				$obj	=>	$this->getUser($obj)
				
			]
			
		];
		
		
	}
	
	public function getIdFromUsername($username) {
				
		return User::where("username","=",$username)->get(['id'])->toArray();

	}
	
	public function formatUserId($uid) {
		
		if (is_numeric($uid)) {
					
			$uid	.=	"-u";
					
		}
		
		return $uid;
		
	}
	
	public function decodeReferralCookieAndCheckReferrer($cookie) {
		
		$out	=	[
			
			"referrer"		=>	"",
			"referred_url"	=>	""
			
		];
		
		$cookie	=	json_decode($cookie,1);
		
		if (is_array($cookie)) {
			
			$cookie	=	$cookie	+ ["u"=>"","r"=>""];

			if ($cookie["u"]) {
				
				$rid	=	$this->getIdFromUsername($cookie["u"]);
				
				
				if ($rid) {
				
					$out["referrer"]	=	$rid;
				
				}
				
			}

			if ($cookie["r"]) {
				
				$out["referred_url"]	=	$cookie["r"];
				
			}
			
		}
			
		return $out;
		
	}

	public function processLoginToken($data) {

		$token	=	tokenAuth::attempt(['email' => $data["email"], 'password' => $data["password"]]);

		if (!is_array($token)) {

			return $token;

		}

		return $this->checkLoginWithToken($token["token"]);

	}
	

	public function checkLoginWithToken($token=null) {
				
		if (!tokenAuth::check()) {
			
			return [];
			
		}
		
		$response					=	['data' => [
			'user' => $this->user()
			]
		];

		if ($token) {
			
			$response["data"]["user"]["token"]	=	$token;
			
		}

		return $response;		
		
	}
	
    public function sendResetLinkEmail($data) {
		
		$data		=	$data	+	["email"=>""];
				
		$validator = Validator::make(
				
			['email'	=>	$data["email"]],
			['email'	=>	'required|email']
				
		);
		
		if ($validator->fails()) {

			return ["error"=>"You didn't type your email address correctly, please try again."];
			
		}
		
		$user	=	User::process("first_name",array("email=?",[$data["email"]]))[0];

		$token	=	md5($data["email"].microtime());

		passwordResets::process([

			"email"	=>	$data["email"],

			"token"	=>	$token

		]);

		$data=array(

			"first_name"	=>	$user['first_name'],
			"email"			=>	$data['email'],
			"link"			=>	"http://".Utils::hostname(1).config('dm.user.returnAddresses.newPassword')."?token=".$token

		);

		Mail::send('user::emails.sendPassword', $data, function($message) Use ($data) {
			
			$message->to($data["email"], $data['first_name'])->subject('Verify your '.config('dm.user.websiteName').' email address.');
			
		});		
				
		return ["success"=>"If we have your email address on file we'll send you an email within 1 minute. If you don't receive an email, please check your email address and then check your junk/spam folder."];

    }
	
	public function resetPassword($data) {
		
		$data =	$data	+	["token"=>"","password1"=>"","password2"=>""];
		
		$setupController =	new setupController;

		if ($data["password1"] <> $data["password2"]) {

			return ["error"	=>	"The 2 passwords you typed don't match."];

		}
		
		if ($setupController->passwordNotValid($data["password1"])) {
			
			return ["error"	=>	$setupController->passwordNotValid($data["password"])];
			
		}
		
		$email		=	passwordResets::process("email",[
			
			"token=?",	[$data["token"]]

		])[0]["email"];
		
		if (!$email) {
			
			return ["error"	=>	"The reset link you are using has expired, please click on Forgotten password to request a new link."];
			
		}
		
		if ($email) {
			
			User::tran("users",[
				
				"password"	=>	Hash::make($data["password1"])
				
			],["email=?",[$email]]);

			passwordResets::process("delete",["email=?",[$email]]);

		}

		return ["success"	=>	"Your password has been changed."];

	}

}