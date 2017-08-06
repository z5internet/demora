<?php namespace z5internet\ReactUserFramework\App;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model {
 
 	public $timestamps = false;

	protected $tables = "error_log";
	
}
