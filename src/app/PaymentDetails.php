<?php namespace z5internet\ReactUserFramework\App;

use Illuminate\Database\Eloquent\Model;

class PaymentDetails extends Model {
 
	protected $table = "payment_details";

	protected $fillable = ['team_id'];

}
