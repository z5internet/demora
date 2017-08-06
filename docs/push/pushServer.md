use z5internet\ReactUserFramework\App\Http\Controllers\PushController;

$pushController = new PushController;

$pushController->pushToChannel($channelName, $cursor, $data);

$pushController->pushToUserChannel($uid, $channelName, $cursor, $data);

$uid = id of user

$channelName = channel name

$cursor = value matches up with a/b from client requesting the data

$data = data to return
