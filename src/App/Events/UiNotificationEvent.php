<?php namespace z5internet\ReactUserFramework\App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

use z5internet\ReactCommon\App\Http\Controllers\Names\Names;

class UiNotificationEvent implements ShouldBroadcast {

    use SerializesModels;

    public $uiNotifications;

    public $users;

    private $id;

    public function __construct($notification) {

        $this->id = $notification['id'];

        $this->uiNotifications = [$this->id => $notification];

        $users = [];

        foreach($notification['b'] as $nb) {

            if (isset($nb['u'])) {

                $users[$nb['u']] = 1;

            }

        }

        $this->users = (new Names)->getUsers($users, app('auth')->id());

    }

	public function broadcastOn()
    {
        return new PrivateChannel('USER_'.$this->uiNotifications[$this->id]['u']);
    }

    public function broadcastAs()
    {
        return 'uiNotification';
    }

}