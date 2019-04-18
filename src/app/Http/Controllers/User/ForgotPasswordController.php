<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\User;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use z5internet\ReactUserFramework\App\Http\Controllers\User\UserController;

use z5internet\ReactUserFramework\App\PasswordResets;

use z5internet\ReactUserFramework\App\Http\Controllers\User\Login\ThrottlesLogins;

class ForgotPasswordController extends Controller
{

    private $email;

    private $token;

    private $user;

    public function sendResetLinkEmail($email) {

        $this->email = $email;

        if (!$this->getUserByEmail()) {

            return $this->sendResetLinkFailedResponse();

        }

        $token = $this->insertNewToken();

        if ($response = $this->sendMail()) {

            return $this->sendResetLinkResponse();

        }
        else
        {

            return $this->sendResetLinkFailedResponse();

        }

    }

    public function reset($data) {

        $this->email = $data['email'];
        $this->token = $data['token'];

        $pwd = $data['password'];

        if ($p = $this->passwordNotValid($pwd)) {

            return $p;

        }

        if (!$this->checkResetToken()) {

            return 'invalid_token_email';

        }

        $u = UserController::getUserByEmail($this->email);

        UserController::updateUser(['password' => app('hash')->make($data['password'])], $u->id);

        $this->deleteExisting();

        (new ThrottlesLogins)->clearLoginAttempts(app('request'));

        return 'reset';

    }

    public function passwordNotValid($pwd) {

        $return = [];

        $leng = 6;

        if (strlen($pwd) < $leng) {

            $return[] = 'Your password should be at least '.$leng.' characters long.';

        }

        if (!preg_match('#[0-9]+#', $pwd)) {

            $return[] = 'Your password must include at least one number.';

        }

        if (!preg_match('#[a-zA-Z]+#', $pwd)) {

            $return[] = 'Your password must include at least one letter.';

        }

        if (!preg_match('#[A-Z]+#', $pwd)) {

            $return[] = 'Your password must include at least one uppercase letter A-Z.';

        }

        if (count($return) > 0) {

            return join(' ', $return);

        }

        return null;

    }

    private function checkResetToken() {

        return $this->getDB()->where('token', $this->token)->where('email', $this->email)->where('created_at', '>', app('db')->raw('subdate(now(), interval 12 hour)'))->first();

    }

    private function sendResetLinkResponse()
    {
        return ['data' => true];
    }

    private function sendResetLinkFailedResponse() {

        return ['data' => true];

    }

    private function createToken() {

        $length = 40;

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return hash_hmac('sha256', $randomString, config('app.key'));

    }

    private function deleteExisting() {

        $this->getDB()->where('email', $this->email)->delete();

    }

    private function insertNewToken() {

        $this->token = $this->createToken();

        $this->getDB()->insert(['email' => $this->email, 'token' => $this->token, 'created_at' => app('db')->raw('now()')]);

    }

    private function getDB() {

        return new PasswordResets();

    }

    private function sendMail() {

        $user = $this->getUserByEmail();

        $data = [
            'email' => $user->email,
            'first_name' => $user->first_name,
            'link' => config('app.url').'/password/reset/'.$this->token,
        ];

        app('mailer')->send('vendor.ruf.email.resetPasswordEmail', $data, function($message) Use ($data) {

            $message->to($data['email'], $data['first_name'])->subject(config('app.name').' - reset password.');

        });

    }

    private function getUserByEmail() {

        if (!$this->user) {

            $this->user = UserController::getUserByEmail($this->email);

        }

        return $this->user;

    }

}
