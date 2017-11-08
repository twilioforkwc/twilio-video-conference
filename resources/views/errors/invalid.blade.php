@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">エラー</div>
                <div class="panel-body">
                    アクセス権限がないか、有効期限が切れています。
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
