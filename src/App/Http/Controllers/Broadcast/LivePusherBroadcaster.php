<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\Broadcast;

use Pusher\Pusher;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Broadcasting\BroadcastException;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Pusher\PusherException;

use Illuminate\Broadcasting\Broadcasters\Broadcaster;

class LivePusherBroadcaster extends Broadcaster
{
    /**
     * The Pusher SDK instance.
     *
     * @var \Pusher
     */
    protected $pusher;

    /**
     * Create a new broadcaster instance.
     *
     * @param  \Pusher  $pusher
     * @return void
     */
    public function __construct(Pusher $pusher)
    {
        $this->pusher = $pusher;
    }

    /**
     * Authenticate the incoming request for a given channel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function auth($request) {

    	$channel_name = $request->input('channel_name');

        if (Str::startsWith($channel_name, ['private-', 'presence-']) &&
            ! $request->user()) {
            throw new HttpException(401);
        }

        $channelName = Str::startsWith($channel_name, 'private-')
                            ? Str::replaceFirst('private-', '', $channel_name)
                            : Str::replaceFirst('presence-', '', $channel_name);

        $channelName = Str::replaceFirst(config('broadcasting.connections.livePusher.app_id').'-', '', $channelName);

        return parent::verifyUserCanAccessChannel(
            $request, $channelName
        );

    }

    /**
     * Return the valid authentication response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $result
     * @return mixed
     */
    public function validAuthenticationResponse($request, $result)
    {

    	$channel_name = $request->input('channel_name');

        $socket_id = $request->input('socket_id');

        if (Str::startsWith($channel_name, 'private')) {
            return $this->decodePusherResponse(
                $this->socket_auth($channel_name, $socket_id)
            );
        }

        return $this->decodePusherResponse(
            $this->presence_auth(
                $channel_name, $socket_id, $request->user()->getAuthIdentifier(), $result)
        );
    }

    public function socket_auth($channel, $socket_id, $custom_data = null)
    {

        $this->validate_channel($channel);
        $this->validate_socket_id($socket_id);

        if ($custom_data) {
            $signature = hash_hmac('sha256', $socket_id.':'.$channel.':'.$custom_data, config('broadcasting.connections.livePusher.secret'), false);
        } else {
            $signature = hash_hmac('sha256', $socket_id.':'.$channel, config('broadcasting.connections.livePusher.secret'), false);
        }

        $signature = array('auth' => config('broadcasting.connections.livePusher.key').':'.$signature);

        if ($custom_data) {
            $signature['channel_data'] = $custom_data;
        }

        return json_encode($signature);
    }

    /**
     * Creates a presence signature (an extension of socket signing).
     *
     * @param string $socket_id
     * @param string $user_id
     * @param mixed  $user_info
     *
     * @return string
     */

    public function presence_auth($channel, $socket_id, $user_id, $user_info = null)
    {
        $user_data = array('user_id' => $user_id);
        if ($user_info) {
            $user_data['user_info'] = $user_info;
        }

        return $this->socket_auth($channel, $socket_id, json_encode($user_data));
    }

    /**
     * Ensure a channel name is valid based on our spec.
     *
     * @param $channel The channel name to validate
     *
     * @throws PusherException if $channel is invalid
     *
     * @return void
     */
    private function validate_channel($channel)
    {
        if (!preg_match('/\A[-a-zA-Z0-9_=@,.;\-]+\z/', $channel)) {
            throw new PusherException('Invalid channel name '.$channel);
        }
    }

    /**
     * Ensure a socket_id is valid based on our spec.
     *
     * @param string $socket_id The socket ID to validate
     *
     * @throws PusherException if $socket_id is invalid
     */
    private function validate_socket_id($socket_id)
    {
        return '';
#        if ($socket_id !== null && !preg_match('/\A\d+\.\d+\z\-/i', $socket_id)) {
#            throw new PusherException('Invalid socket ID '.$socket_id);
#        }
    }

    /**
     * Decode the given Pusher response.
     *
     * @param  mixed  $response
     * @return array
     */
    protected function decodePusherResponse($response)
    {
        return json_decode($response, true);
    }

    /**
     * Broadcast the given event.
     *
     * @param  array  $channels
     * @param  string  $event
     * @param  array  $payload
     * @return void
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {

        $socket = Arr::pull($payload, 'socket');

        foreach($channels as $k => $channel_name) {

            if (!Str::startsWith($channel_name, ['private-', 'presence-'])) {

                continue;

            }

            $c = explode('-', (string) $channel_name);

            if (count($c) <> 2) {

                abort($channel_name . 'is not a valid channel');

            }

            $channels[$k] = join('-', [$c[0], config('broadcasting.connections.livePusher.app_id'), $c[1]]);

        }

        $response = $this->pusher->trigger(
            $this->formatChannels($channels), $event, $payload, $socket, true
        );

        if ((is_array($response) && $response['status'] >= 200 && $response['status'] <= 299)
            || $response === true) {
            return;
        }

        throw new BroadcastException(
            is_bool($response) ? 'Failed to connect to Pusher.' : $response['body']
        );

    }

    /**
     * Get the Pusher SDK instance.
     *
     * @return \Pusher
     */
    public function getPusher()
    {
        return $this->pusher;
    }
}
