@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">ビデオチャットを変更する</div>
                <div class="panel-body">
                    <form class="" action="/channels/{{ $channel->id }}" method="POST">
                        <input type="hidden" name="_method" value="PUT">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('friendly_name') ? ' has-error' : '' }}">
                            <label for="friendly_name">ビデオチャット名</label>
                            <div class="channel-control">
                                <input class="form-control" type="text" name="friendly_name" value="{{ $channel->friendly_name }}">
                                @if ($errors->has('friendly_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('friendly_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('expires_date') ? ' has-error' : '' }}">
                            <label for="expires_date">チャンネル有効期限</label>
                            <div>
                                <div class="datetime-control">
                                    <input id="from_date" class="form-control" type="date" name="from_date" value="{{ date('Y-m-d', strtotime($channel->from_date)) }}">
                                    <input id="from_time" class="form-control" type="time" name="from_time" value="{{ date('H:i', strtotime($channel->from_date)) }}">
                                    <span> から </span>
                                    <input id="to_date" class="form-control" type="date" name="to_date" value="{{ date('Y-m-d', strtotime($channel->to_date)) }}">
                                    <input id="to_time" class="form-control" type="time" name="to_time" value="{{ date('H:i', strtotime($channel->to_date)) }}">
                                </div>
                                @if ($errors->has('expires_date'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('expires_date') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success" type="submit" name="button">変更</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="">
                <a href="/channels">
                    <button class="btn btn-primary" type="button" name="button">戻る</button>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
