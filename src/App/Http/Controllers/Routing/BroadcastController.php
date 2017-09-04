<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\Broadcast;

use Illuminate\Http\Request;
use z5internet\ReactUserFramework\App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Broadcast;

class BroadcastController extends Controller
{
    /**
     * Authenticate the request for channel access.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        return app(\Illuminate\Contracts\Broadcasting\Factory::class)->auth($request);
    }

    public function createToken(Request $request) {


    }

}
