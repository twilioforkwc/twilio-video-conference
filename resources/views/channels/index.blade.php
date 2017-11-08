@extends('layouts.app')

@section('content')
<style media="screen">
    .room-buttons > .btn {
        margin: 5px 0;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <ul class="list-group channel-list">
                @foreach($channels as $data)
                <li class="list-group-item">
                    <div class="room-title">
                        <h4>
                            {{ $data->friendly_name }}
                        </h4>
                    </div>
                    <div class="room-url">
                        @if (!$data->deleted_flg)
                        <a href="/channels/{{ $data->channel_name }}" id="clip-{{ $data->id }}">
                            {{ Config::get('app.url').'/channels/'.$data->channel_name }}
                        </a>
                        @else
                        <span id="clip-{{ $data->id }}">
                            {{ Config::get('app.url').'/channels/'.$data->channel_name }}
                        </span>
                        @endif
                    </div>
                    <div class="room-buttons">
                        <span class="click click-copy-to-clipboard btn btn-primary btn-sm" data-clip="{{ $data->id }}"><i class="fa fa-clipboard"></i>&nbsp;クリップボードにコピー</span>
                        @if (!$data->deleted_flg)
                        <a class="btn btn-primary btn-sm" href="/channels/{{ $data->channel_name }}" target="_blank"><i class="fa fa-clone"></i> 別タブで開く</a>
                        @else
                        <a class="btn btn-primary btn-sm" href="#" disabled="disabled"><i class="fa fa-clone"></i> 別タブで開く</a>
                        @endif
                    </div>
                    <div class="control-box">
                        <div style="padding: 5px;">
                            <span class="badge {{ ($data->deleted_flg) ? 'badge-expired' : 'badge-primary' }}">{{ ($data->deleted_flg) ? '終了' : date("Y年m月d日 H時i分", strtotime($data->expires_date)).'まで有効' }}</span>
                        </div>
                        @if ($data->user->id === Auth::user()->id)
                        <div>
                            @if (!$data->deleted_flg)
                            <a class="btn btn-success" href="/channels/{{ $data->id }}/edit">
                                編集
                            </a>
                            <!-- <button class="btn btn-success" type="button" name="button">
                            </button> -->
                            @else
                            <button class="btn btn-success" type="button" name="button" disabled="disabled">編集</button>
                            @endif
                            <button type="button" id="modal-button" class="btn btn-danger" data-toggle="modal" data-target="#modal-{{ $data->id }}">削除</button>
                        </div>
                        <form method="POST" action="{{ Config::get('app.admin.prefix') }}/channels/{{ $data->id }}" accept-charset="UTF-8" class="form-horizontal">
                            {{ csrf_field() }}
                            <input name="_method" type="hidden" value="DELETE">
                            <div class="modal fade" id="modal-{{ $data->id }}">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">元に戻せません。削除してよろしいですか?</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-danger">削除する</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                </li>
                @endforeach
            </ul>
            @if($channels->count() === 0)
            登録されたビデオチャットはまだありません。
            @endif
            <div class="">
                <a href="/channels/create">
                    <button class="btn btn-primary" type="button" name="button">チャットルームを作る</button>
                </a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('.click-copy-to-clipboard').on('click', function(){
        copyTextToClipboard($('#clip-'+$(this).attr('data-clip')).text());
        $.notify($('#clip-'+$(this).attr('data-clip')).text()+'をクリップボードにコピーしました。', 'success');
    });
});
</script>
@endsection
