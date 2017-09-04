<?php namespace z5internet\ReactUserFramework\App\Events;

use z5internet\ReactUserFramework\App\ErrorLog;

use Illuminate\Queue\SerializesModels;

class ErrorLogged {

    use SerializesModels;

    public $error;

    public function __construct(ErrorLog $error) {

        $this->error = $error;

    }

}