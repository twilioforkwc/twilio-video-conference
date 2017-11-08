<!-- Twilio Chat -->
<script>
function loadFrame(client, channel) {
    // Configuration for Chat Frame
    const frameConfiguration = {
        channel: {
            chrome: {
                closeCallback: channelSid => {
                    chatFrame.unloadChannelBySid(channelSid);
                }
            },
            visual: {
                colorTheme: 'DarkTheme'
            }
        }
    };

    // Create the Chat Frame instance
    const chatFrame = Twilio.Frame.createChat(client, frameConfiguration);
    chatFrame.loadChannel('#myChatFrame', channel);
}

loadChat();
function loadChat() {
    var token = "{{ $accessToken }}";
    // Create a Chat Client
    Twilio.Chat.Client.create(token).then(client => {
        // Add channel joined listener
        client.on('channelJoined', channel => {
            if (channel.uniqueName == "{{ $channelName }}") {
                loadFrame(client, channel);
            }
        });
        var channelName = "{{ $channelName }}";
        return client.getChannelByUniqueName(channelName).catch(err => {
            return client.createChannel({
                uniqueName: channelName,
                friendlyName: "{{ $friendlyName }}"
            });
        });
    })
    // Join the created channel
    .then(channel => {
        // console.log("testest3");
        return channel.join();
    });
}
</script>
