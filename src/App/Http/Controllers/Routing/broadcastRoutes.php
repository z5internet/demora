<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\Routing;

use Illuminate\Http\Request;
use z5internet\ReactUserFramework\App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Broadcast;

class broadcastRoutes extends Controller
{
    /**
     * Authenticate the request for channel access.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {

        return [
            'data' => app(\Illuminate\Contracts\Broadcasting\Factory::class)->auth($request),
        ];

    }

}
