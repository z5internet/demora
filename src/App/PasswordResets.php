<?php namespace z5internet\ReactUserFramework\App;

use Illuminate\Database\Eloquent\Model;

class PasswordResets extends Model {

 	public $timestamps = false;

	protected $table = "password_resets";

}
