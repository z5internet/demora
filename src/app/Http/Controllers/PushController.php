<?php namespace z5internet\ReactUserFramework\App\Http\Controllers;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use stdClass;

use z5internet\ReactUserFramework\App\Http\Controllers\OnlineController;

use z5internet\ReactUserFramework\App\Push;

class PushController extends Controller {

	private $l = 0;

	public function pushToChannel($channel, $data=null) {

		$this->writeToPushDB(null, $channel, $data);

	}

	public function pushToUserChannel($uid, $channel=null, $data=null) {

		$this->writeToPushDB($uid, $channel, $data);

	}

	private function pushChannel($key, $cursor, $data) {

		$cacheData = $this->createCacheData($cursor, $data);

		return $this->putCache($key, $cacheData);

	}

	private function writeToPushDB($uid, $channel, $data) {

		$getCurrentPusherID = $this->getCurrentPusherID();

		$dbData = [];

		if ($data) {

			$d = [];

			if ($uid) {
				$d['uid'] = explode('-', $uid)[0];
			}

			$d['fromConnection'] = 	$getCurrentPusherID;
			$d['channel'] = $channel;
			$d['data'] = json_encode($data);

			array_push($dbData, $d);

		}
		else
		{

			$data = $uid?$uid:$channel;

			foreach($data as $tdb) {

				$d = [];

				if (isset($tdb['uid'])) {
					$d['uid'] = explode('-', $tdb['uid'])[0];
				}

				$d['fromConnection'] = $getCurrentPusherID;
				$d['channel'] = $tdb['channel'];
				$d['data'] = json_encode($tdb['data']);

				array_push($dbData, $d);

			}

		}

		$db = Push::insert($dbData);

		foreach ($dbData as $tdb) {

			$this->deleteCache($tdb['channel'], array_get($tdb, 'uid'));

		}

	}

	public function get($args) {

		$st = time();

		$out = ['c' => []];

		$this->setPidKey();

		(new OnlineController)->setOnline();

		while(time()-30 < $st) {

			if ($this->getPidKey() != $this->getCurrentPusherConnection()) {

				return ['stop' => 1];

			}


			if (isset($args['c'])) {

				$out['c'] = $this->getSubscribedChannels($args);

			}

			if (isset($args['c']) && isset($args['c']['u']) && app('auth')->check()) {

				$userChannels = $this->getSubscribedChannels($args, app('auth')->id());

				if (count($userChannels)>0) {

					$out['c']['u'] = $userChannels;

				}


			}

			if (count($out['c'])>0) {
				break;
			}

			sleep(1);

		}

		$out['l'] = $this->l;

		return $out;

	}

	public function deleteOldFromPush() {

		$channels = [];

		$stop = false;

		$latest_id = 0;

		while (!$stop) {

			$db = Push::where('created_at', '<', app('db')->raw('subdate(now(), interval 1 hour)'))->take(5000)->orderBy('id', 'asc')->get();

			if (count($db) < 5000) {

				$stop = true;

			}

			foreach($db as $tdb) {

				$channel = $this->getCacheKey($tdb->channel, $tdb->uid);

				$channels[$channel] = 1;

				$latest_id = $tdb->id;

			}

			Push::where('id', '<=', $latest_id)->delete();

		}

		foreach (array_keys($channels) as $ck) {

			app('cache')->forget($ck);

		}

	}

	private function getSubscribedChannels($args, $uid = null) {

		$out = [];

		$channels = array_keys($uid?$args['c']['u']:$args['c']);

		foreach ($channels as $ch) {

			if ($ch == 'u') {

				continue;

			}

			$db = $this->getPushDataFromDB($args, $uid, $ch);

			if (count($db)>0) {

				foreach($db as $k => $d) {

					if ($d['id'] > $this->l) {

						$this->l = $d['id'];

					}

					if ($d['fromConnection'] <> $this->getCurrentPusherConnection()) {

						unset($d['fromConnection']);

						$db[$k]['data'] = json_decode($d['data']);

						if (!isset($out[$ch])) {

							$out[$ch] = [];

						}

						if (isset($db[$k]['data']->users)) {

							$db[$k]['data']->users = app('Names')->getUsers((array) $db[$k]['data']->users, $uid);

						}

						array_push($out[$ch], $db[$k]);

					}

				}

			}

		}

		return $out;

	}

	private function getPushDataFromDB($args, $uid, $channel) {

		$db = $this->getCache($channel, $uid);

		if (!is_array($db)) {

			$db = Push::where('channel', $channel);
			if ($uid) {
				$db = $db->where('uid', $uid);
			}
			$db = $db->get(['id','data','fromConnection'])->toArray();

			$this->putCache($channel, $uid, $db);

		}

		$out = [];

		foreach ($db as $d) {

			if ($d['id'] > $args['l']) {

				array_push($out, $d);

			}

		}

		return $out;

	}

	private function getCacheKey($channel, $uid) {

		$k = 'Push-'.$channel.'-';

		if ($uid) {

			$uid = $this->formatUID($uid);

			$k .= $uid;

		}

		return $k;

	}

	private function getCache($channel, $uid) {

		$ck = $this->getCacheKey($channel, $uid);

		return app('cache')->get($ck);

	}

	private function putCache($channel, $uid, $data) {

		$ck = $this->getCacheKey($channel, $uid);

		return app('cache')->put($ck, $data, 60);

	}

	private function deleteCache($channel, $uid) {

		$ck = $this->getCacheKey($channel, $uid);

		return app('cache')->forget($ck);

	}

	private function formatUID($uid) {

		return explode('-', $uid)[0].'-u';

	}

	private function getCurrentPusherID() {

		return $this->getHeaderByName('x-pusher-id') || '';

	}

	private function getCurrentPusherConnection() {

		return $this->getHeaderByName('x-pusher-connection-id');

	}

	private function getHeaderByName($name) {

		$headers = getallheaders();

		foreach(array_keys($headers) as $th) {

			if (strtolower($th) == strtolower($name)) {

				return $headers[$th];

			}

		}

	}

	private function getPidKey() {

		$key = 'Pusher_pid_'.$this->getCurrentPusherID();

		return app('cache')->get($key);

	}

	private function setPidKey() {

		$key = 'Pusher_pid_'.$this->getCurrentPusherID();
		$keyConn = $this->getCurrentPusherConnection();

		$old = 0;

		while ($keyConn > $old) {

			$old = $this->getPidKey();

			if ($old && $keyConn <= $old) {

				break;

			}
			else
			{

				app('cache')->put($key, $keyConn, 60);

			}

		}

	}

}
