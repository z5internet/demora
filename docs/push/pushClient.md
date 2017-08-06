import pusherChannel from 'rufUtils/pusherChannel';

pusherChannel.addUserChannel(channelName, channelData, callback);	

pusherChannel.addChannel(channelName, channelData, callback);	

pusherChannel.deleteUserChannel(channelName)

pusherChannel.deleteChannel(channelName)

(string)channelName - Name of the channel to subscribe to

(object)channelData - {
	a: (any) get data after cursor	
	b: (any) get data before cursor	
}

(function)callback(data) data returned from channel
