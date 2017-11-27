<script>
/**
* Get a MediaStream containing a MediaStreamTrack that represents the user's
* screen.
*
* This function sends a "getUserScreen" request to our Chrome Extension which,
* if successful, responds with the sourceId of one of the specified sources. We
* then use the sourceId to call getUserMedia.
*
* @param {Array<DesktopCaptureSourceType>} sources
* @param {string} extensionId
* @returns {Promise<MediaStream>} stream
*/
function getUserScreen(sources, extensionId) {
    const request = {
        type: 'getUserScreen',
        sources: sources
    };
    return new Promise((resolve, reject) => {
        chrome.runtime.sendMessage(extensionId, request, response => {
            console.log(response);
            switch (response && response.type) {
                case 'success':
                resolve(response.streamId);
                break;

                case 'error':
                reject(new Error(error.message));
                break;

                default:
                reject(new Error('Unknown response'));
                break;
            }
        });
    }).then(streamId => {
        return navigator.mediaDevices.getUserMedia({
            video: {
                mandatory: {
                    chromeMediaSource: 'desktop',
                    chromeMediaSourceId: streamId,
                    // You can provide additional constraints. For example,
                    maxWidth: 1920,
                    maxHeight: 1080,
                    maxFrameRate: 10,
                    minAspectRatio: 1.77
                }
            }
        });
    });
}
const { connect, LocalVideoTrack } = Twilio.Video;

// Option 1. Provide the screenLocalTrack when connecting.
async function option1() {
    const stream = await getUserScreen(['screen', 'tab'], "{{ $request->chromeid }}");
    const screenLocalTrack = new LocalVideoTrack(stream.getVideoTracks()[0]);

    screenLocalTrack.once('stopped', () => {
        loadVideo();
    })
    var previewContainer = document.getElementById('local-media');

    const room = await connect("{{ $videoToken }}", {
        name: "{{ $channelName }}",
        tracks: [screenLocalTrack]
    });

    return room;
}

// Option 2. First connect, and then add screenLocalTrack.
async function option2() {

    const stream = await getUserScreen(['screen', 'tab'], "{{ $request->chromeid }}");
    const screenLocalTrack = new LocalVideoTrack(stream.getVideoTracks()[0]);

    screenLocalTrack.once('stopped', () => {
        // Handle "stopped" event.
        loadVideo();
        $('#screen-toggle').removeClass('active');
    });

    if (activeRoom) {
        activeRoom.localParticipant.addTrack(screenLocalTrack);
    };

    return activeRoom;
}
</script>
