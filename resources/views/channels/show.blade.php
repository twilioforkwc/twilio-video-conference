@extends('layouts.app')

@section('content')
<style media="screen">
    .screen-share-input > form > div {
        padding: 10px 0;
    }
</style>
@if ($request->chromeid)
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
@else
<div style="display: flex; justify-content: center; align-items: center;">
    <div style="">
        <div class="panel panel-default">
            <div class="panel-heading">画面共有IDを入力してください</div>
            <div class="panel-body screen-share-input" style="max-height: 200px; overflow: scroll;">
                <form class="form-group" action="{{ Request::fullUrl() }}" method="get">
                    <div>
                        <label for=""></label>
                        <input class="form-control" type="text" name="chromeid" value="" required>
                    </div>
                    <div>
                        <button class="btn btn-success" type="submit" name="button">入力したIDを使用する</button>
                        <a class="btn btn-danger" href="{{ Request::fullUrl() }}?chromeid=none">画面共有を使用しない</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@include('modules.twilio_libs')
@include('modules.chat')
@include('modules.video')
@include('modules.screen')
