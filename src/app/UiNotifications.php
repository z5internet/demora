<?php namespace z5internet\ReactUserFramework\App;

use Illuminate\Database\Eloquent\Model;

class UiNotifications extends Model {
 
 	public $incrementing = false;

	protected $table = "ui_notifications";

	protected $fillable = ['id'];

}
