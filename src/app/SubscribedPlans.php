<?php namespace z5internet\ReactUserFramework\App;

use Illuminate\Database\Eloquent\Model;

class SubscribedPlans extends Model {
 
	protected $table = "subscribed_plans";

	protected $fillable = ['team_id'];
	
}
