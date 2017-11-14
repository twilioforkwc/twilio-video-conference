@extends('layouts.app')

@section('content')
<style media="screen">
.channel-control {
    padding: 5px;
}
.datetime-control {
    display: flex;
    flex-wrap: nowrap;
    padding: 5px;
}
.datetime-control > input:nth-of-type(2n) {
    flex: 1;
}
.datetime-control > input:nth-of-type(2n+1) {
    flex: 2;
}
.datetime-control > input {
    margin: 0 5px;
}
.datetime-control > span {
    display: flex;
    align-items: center;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">ビデオチャットルームを作成</div>
                <div class="panel-body">
                    <form class="" action="/channels" method="post">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('friendly_name') ? ' has-error' : '' }}">
                            <label for="friendly_name">チャンネル名</label>
                            <div class="channel-control">
                                <input class="form-control" type="text" name="friendly_name" value="{{ old('friendly_name') }}">
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
                                    <input id="from_date" class="form-control" type="date" name="from_date" value="{{ old('from_date') }}">
                                    <input id="from_time" class="form-control" type="time" name="from_time" value="{{ old('from_time') }}">
                                    <span> から </span>
                                    <input id="to_date" class="form-control" type="date" name="to_date" value="{{ old('to_date') }}">
                                    <input id="to_time" class="form-control" type="time" name="to_time" value="{{ old('to_time') }}">
                                </div>
                                <div class="datetime-control">
                                </div>
                                @if ($errors->has('expires_date'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('expires_date') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit" name="button">作成</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="">
                <a href="/channels">
                    <button class="btn btn-success" type="button" name="button">戻る</button>
                </a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('#from_date').change(function(){
        $('#to_date').val($(this).val());
    });
    $('#from_time').change(function(){
        var date = new Date('1970/01/01 '+$(this).val());
        date.setMinutes(date.getMinutes()+60);
        $('#to_time').val(('00' + date.getHours()).slice(-2)+':'+('00' + date.getMinutes()).slice(-2));
    });
});
</script>
@endsection
