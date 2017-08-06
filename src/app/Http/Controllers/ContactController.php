<?php namespace z5internet\ReactUserFramework\App\Http\Controllers;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

class ContactController extends Controller
{

	public function contactUs($data) {

		$data	=	$data+array(
			"name"		=>	"",
			"email"		=>	"",
			"message"	=>	""
		);

		$dismessage="";

		if (!$this->verify_email($data["email"])) {

			$data["email"]	=	"";

		}

		$data["name"]		= $this->remove_injected_spam_headers($data["name"]);

		$data["email"]		= $this->remove_injected_spam_headers($data["email"]);

		$data["message"] 	= $this->remove_injected_spam_headers($data["message"]);

		$data["message"] 	= html_entity_decode($data["message"]);

		if (
			$data["name"] &&
			$data["email"] &&
			$data["message"]) {

			$id=0;

			if (app('auth')->check()) {
				app('auth')->user()->id;
			}

	        app('mailer')->send('vendor.ruf.email.supportEmail', [
	        	'name' => $data["name"],
	        	'email' => $data["email"],
	        	'content' => $data["message"],
	        	'id' => $id
        	],
        	function ($message) Use ($data) {

	            $message->from(config('mail.from.address'), config('app.name'));

	            $message->replyTo($data["email"],$data["name"]);

	            $message->subject(substr($data["message"],0,40)."...");

	            $message->to(config('mail.from.address'));

        	});

			return array("data" => ["success"=>"Your message was sent"]);

		}
		else
		{

			return [
				"data" => [],
				"errors"=>[
					[
						"message" => "There was an error sending your message. Please type your message in the boxes and check your email address."
					]
				]
			];

		}

	}

	private function verify_email($email) {

		$email	=	preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $email);

		return $email;

	}

	private function remove_injected_spam_headers($value, $check_all_patterns = true){

		$patterns[0] = '/content-type:/';
		$patterns[1] = '/to:/';
		$patterns[2] = '/cc:/';
		$patterns[3] = '/bcc:/';
		if ($check_all_patterns)
		{
			$patterns[4] = '/\r/';
			$patterns[5] = '/%0a/';
			$patterns[6] = '/%0d/';
		}
		return preg_replace($patterns, "", $value);

	}

}
