<?php namespace z5internet\ReactUserFramework\App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {

	protected $table = "invoices";

	protected $fillable = ['converted_total', 'total'];

}
