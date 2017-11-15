<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'channel_name',
        'friendly_name',
        'channel_sid',
        'from_date',
        'to_date',
    ];

    /**
     * [user 動的プロパティ]
     * @return [User] [該当チャンネルを登録したユーザーを返却します]
     */
    public function user()
    {
        return $this::belongsTo('App\User');
    }

    /**
     * [getRecords ログイン中ユーザーが登録したチャンネル一覧を返却します]
     * @return [object] [該当するチャンネルリストを返却します]
     */
    public function getRecords()
    {
        return $this::Login()->Desc('from_date')->get();
    }

    /**
     * [getRecords 期限切れチャンネル一覧を返却します]
     * @return [object] [該当するチャンネルリストを返却します]
     */
    public function getRecordsExpired()
    {
        return $this::where('to_date', '<', date("Y-m-d H:i:s"))
                    ->where('deleted_flg', false)
                    ->get();
    }

    /**
     * [getRecordById 指定したIDのチャンネルがログイン中ユーザーによって登録されたものであれば返却します]
     * @param  [integer] $id [一意のレコードIDを指定します]
     * @return [object]      [該当するチャンネルを返却します]
     */
    public function getRecordById($id)
    {
        return $this::Login()->where('id', $id)->first();
    }

    /**
     * [getRecordByChannelName 指定したチャンネル名のチャンネルがログイン中ユーザーによって登録されたものであれば返却します]
     * @param  [string] $channel_name [一意のチャンネル名を指定します]
     * @return [object]               [該当するチャンネルを返却します]
     */
    public function getRecordByChannelName($channel_name)
    {
        return $this::where('channel_name', $channel_name)
                    ->where('deleted_flg', false)
                    ->where('from_date', '<',date("Y-m-d H:i:s"))
                    ->where('to_date', '>',date("Y-m-d H:i:s"))
                    ->first();
    }

    /**
     * [addRecord 新規チャンネルを登録します]
     * @param  [object] $request      [Requestクラスから渡されたパラメータを指定します]
     * @param  [string] $channel_name [一意のチャンネル名を指定します]
     * @return [object]               [登録したチャンネルを返却します]
     */
    public function addRecord($request, $channel_name) {
        return $this::create([
            'user_id' => \Auth::user()->id,
            'channel_name' => $channel_name,
            'friendly_name' => $request->friendly_name,
            'channel_sid' => $request->channel_sid,
            'from_date' => date("Y-m-d H:i:s", strtotime("{$request->from_date} $request->from_time")),
            'to_date' => date("Y-m-d H:i:s", strtotime("{$request->to_date} $request->to_time")),
        ]);
    }

    /**
     * [updateRecord チャンネル名を変更します]
     * @param  [object] $request [Requestクラスから渡されたパラメータを指定します]
     * @param  [object] $model   [変更対象のチャンネルモデルを指定します]
     * @return [object]          [変更したチャンネルを返却します]
     */
    public function updateRecord($request, $model) {
        $model->friendly_name = $request->friendly_name;
        $model->from_date = date("Y-m-d H:i:s", strtotime("{$request->from_date} $request->from_time"));
        $model->to_date = date("Y-m-d H:i:s", strtotime("{$request->to_date} $request->to_time"));
        return $model->save();
    }

    /**
     * [deleteRecord チャンネルを削除します]
     * @param  [integer] $id [一意のレコードIDを指定します]
     * @return [boolean]     [削除結果を真偽値で返却します]
     */
    public function deleteRecord($model)
    {
        // 削除ボタンがダブルクリックされたときなど、オブジェクトがない場合を考慮してのチェック
        if ($model) {
            return $model->delete();
        }
        return true;
    }

    /**
     * [scopeLogin ログインチェックのクエリを付与します]
     * @param  [Query] $query [クエリインスタンスを指定します]
     * @return [Query]        [任意の検索条件を付与したクエリインスタンスを返却します]
     */
    public function scopeLogin($query)
    {
        return $query->where('user_id', \Auth::user()->id);
    }

    /**
     * [scopeDesc ソート条件を降順にするクエリを付与します]
     * @param  [Query] $query [クエリインスタンスを指定します]
     * @return [Query]        [任意の検索条件を付与したクエリインスタンスを返却します]
     */
    public function scopeDesc($query, $field = null)
    {
        if ($field) {
            return $query->orderBy($field, 'desc');
        }
        return $query->orderBy('id', 'desc');
    }

}
