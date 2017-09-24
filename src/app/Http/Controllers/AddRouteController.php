<?php namespace z5internet\ReactUserFramework\App\Http\Controllers;

use Route;

class AddRouteController extends Controller {

    public function __construct($app) {

        $this->app = $app;

    }

    public function __call($method, $args) {

        call_user_func_array([$this->app->router, $method], $args);

    }

}