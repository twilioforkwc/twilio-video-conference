@extends('layouts.app')

@section('content')
<style media="screen">
    .screen-share-input > form > div {
        padding: 10px 0;
    }
</style>
<div class="container" style="height: 80%;">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">Video（{{ $friendlyName }}）</div>
                <div class="panel-body">
                    <div id="controls">
                        <div id="preview">
                            <div id="local-media"></div>
                            <div class="">
                                <div class="preview-control">
                                    <span id="video-toggle" class="active"><i class="fa fa-video-camera"></i></span>
                                </div>
                                <div class="preview-control">
                                    <span id="audio-toggle" class="active"><i class="fa fa-microphone"></i></span>
                                </div>
                                <div class="preview-control">
                                    <span id="screen-toggle"><i class="fa fa-tv"></i></span>
                                    <div style="padding: 10px;">
                                        <label for="chromeid">Input Extension ID for Screen Share</label>
                                        <input id="chromeid" class="form-control" type="text" name="chromeid" value="" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="room-controls">
                        </div>
                    </div>
                    <hr>
                    <div id="remote-media" style="display: flex;"></div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Activity Log</div>
                <div class="panel-body" style="max-height: 200px; overflow: scroll;">
                    <div id="log"></div>
                </div>
            </div>
            <div class="">
                <a href="/channels">
                    <button class="btn btn-success" type="button" name="button">退室する</button>
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div id="myChatFrame"></div>
        </div>
    </div>
</div>
@endsection

@include('modules.twilio_libs')
@include('modules.chat')
@include('modules.video')
@include('modules.screen')
