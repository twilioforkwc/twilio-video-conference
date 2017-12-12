<!-- Twilio Video -->
<script>
var activeRoom;
var previewTracks;
var identity;
var roomName;

function attachTracks(tracks, container) {
    tracks.forEach(function(track) {
        container.appendChild(track.attach());
    });
}

function attachParticipantTracks(participant, container) {
    var tracks = Array.from(participant.tracks.values());
    attachTracks(tracks, container);
}

function detachTracks(tracks) {
    tracks.forEach(function(track) {
        track.detach().forEach(function(detachedElement) {
            detachedElement.remove();
        });
    });
}

function detachParticipantTracks(participant) {
    var tracks = Array.from(participant.tracks.values());
    detachTracks(tracks);
}

// Check for WebRTC
if (!navigator.webkitGetUserMedia && !navigator.mozGetUserMedia) {
    alert('WebRTC is not available in your browser.');
}

// When we are about to transition away from this page, disconnect
// from the room, if joined.
window.addEventListener('beforeunload', leaveRoomIfJoined);

loadVideo();
function loadVideo() {
    identity = "{{ $userName }}";
    roomName = "{{ $channelName }}";
    var connectOptions = { name: roomName };

    Twilio.Video.connect("{{ $videoToken }}", connectOptions).then(roomJoined, function(error) {
        log('Could not connect to Twilio: ' + error.message);
    });
}

// Successfully connected!
function roomJoined(room) {
    activeRoom = room;

    log("Joined as '" + identity + "'");

    // Draw local video, if not already previewing
    var previewContainer = document.getElementById('local-media');
    if (!previewContainer.querySelector('video')) {
        console.log(room.localParticipant);
        attachParticipantTracks(room.localParticipant, previewContainer);
    }

    room.participants.forEach(function(participant) {
        log("Already in Room: '" + participant.identity + "'");
        var previewContainer = document.getElementById('remote-media');
        attachParticipantTracks(participant, previewContainer);
    });

    // When a participant joins, draw their video on screen
    room.on('participantConnected', function(participant) {
        log("Joining: '" + participant.identity + "'");
    });

    room.on('trackAdded', function(track, participant) {
        log(participant.identity + " added track: " + track.kind);
        var previewContainer = document.getElementById('remote-media');
        attachTracks([track], previewContainer);
    });

    room.on('trackRemoved', function(track, participant) {
        log(participant.identity + " removed track: " + track.kind);
        detachTracks([track]);
    });

    // When a participant disconnects, note in log
    room.on('participantDisconnected', function(participant) {
        log("Participant '" + participant.identity + "' left the room");
        detachParticipantTracks(participant);
    });

    // When we are disconnected, stop capturing local video
    // Also remove media for all remote participants
    room.on('disconnected', function() {
        log('Left');
        detachParticipantTracks(room.localParticipant);
        room.participants.forEach(detachParticipantTracks);
        activeRoom = null;
    });

}

// Activity log
function log(message) {
    $('#log').html($('#log').html()+'<p>&gt;&nbsp;' + message + '</p>');
}

function leaveRoomIfJoined() {
    if (activeRoom) {
        activeRoom.disconnect();
    }
}

function switchVideoMute(muteFlg) {
    const localParticipant = activeRoom.localParticipant;
    localParticipant.videoTracks.forEach((track, trackId) => {
        if (muteFlg) {
            track.disable();
        } else {
            track.enable();
        }
        // track.on('disabled', function() {
        //     console.log('Video was muted.');
        // });
    });
}

function switchAudioMute(muteFlg) {
    const localParticipant = activeRoom.localParticipant;
    localParticipant.audioTracks.forEach((track, trackId) => {
        if (muteFlg) {
            track.disable();
        } else {
            track.enable();
        }
        // track.on('disabled', function() {
        //     console.log('Audio was muted.');
        // });
    });
}
$(document).ready(function(){
    $('#video-toggle').on('click', function(){
        if ($(this).hasClass('active')) {
            switchVideoMute(true);
            $(this).removeClass('active');
        } else {
            switchVideoMute(false);
            $(this).addClass('active');
        };
    });
    $('#audio-toggle').on('click', function(){
        if ($(this).hasClass('active')) {
            switchAudioMute(true);
            $(this).removeClass('active');
        } else {
            switchAudioMute(false);
            $(this).addClass('active');
        };
    });
    $('#screen-toggle').on('click', function(){
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            option2($('#chromeid').val());
            $(this).addClass('active');
        };
    });
});
</script>
