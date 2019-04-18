<?php namespace z5internet\ReactUserFramework\App;

use Illuminate\Database\Eloquent\Model;

class TwoFACodes extends Model {

	protected $table = "twofa_codes";

	public $timestamps = false;

}
