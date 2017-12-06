@extends('layouts.app')

@section('content')
<style media="screen">
    html {
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
    body {
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
    #app {
        height: 100%;
    }
    .navbar {
        margin: 0;
    }
</style>
<div class="content-wrapper">
    <div class="left-pane">
        <div style="width: 100%; height: 100%;">
            <div id="remote-media"></div>
        </div>
    </div>
    <div class="right-pane">
        <div id="room-controls">
            <div>
                <div id="myChatFrame"></div>
            </div>
        </div>
        <div id="screen-share-id">
            <label for="">Enter Your Extension ID for Screen Share</label>
            <input id="chromeid" class="form-control" type="text" name="" value="">
        </div>
        <div id="preview">
            <div class="preview-bk">
                <div id="local-media"></div>
            </div>
            <div class="preview-ft">
                <div class="preview-blank">
                </div>
                <div class="preview-control">
                    <div>
                        <div id="video-toggle" class="active">
                            <span><i class="fa fa-video-camera"></i></span>
                        </div>
                    </div>
                    <div>
                        <div id="audio-toggle" class="active">
                            <span><i class="fa fa-microphone"></i></span>
                        </div>
                    </div>
                    <div>
                        <div id="screen-toggle">
                            <span><i class="fa fa-tv"></i></span>
                        </div>
                    </div>
                    <div>
                        <div style="background-color: #ff0000;">
                            <a href="{{ Config::get('app.url') }}/channels">
                                <span id="screen-toggle" style="color: white;"><i class="fa fa-phone"></i></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('modules.twilio_libs')
@include('modules.chat')
@include('modules.video')
@include('modules.screen')
