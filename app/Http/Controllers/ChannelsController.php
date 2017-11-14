<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Channel;
use App\MyLibs\TwilioApiManager;
use App\MyLibs\AuthInfoManager;

class ChannelsController extends Controller
{

    protected $channel_model;
    protected $twilio_api;
    protected $auth_info;

    /**
     * [__construct construct channel model and twilio api.]
     * Constructor Injection Channel $channel [チャンネルモデルクラス]
     */
    public function __construct(Channel $channel)
    {
        $this->channel_model = $channel;
        $this->twilio_api = new TwilioApiManager();
        $this->auth_info = new AuthInfoManager();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $channels = $this->channel_model->getRecords();
        } catch (\Exception $e) {
            $errorcd = 'E5201';
            \Log::error(\Lang::get("errors.{$errorcd}"), [$e]);
        }
        return view('channels.index', compact('channels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('channels.create');
    }

    /**
     * Store a newly created resource in local and Twilio storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'friendly_name' => 'bail|required',
            'from_date' => 'bail|required',
            'from_time' => 'bail|required',
            'to_date' => 'bail|required',
            'to_time' => 'bail|required',
        ], [
            'friendly_name.required' => 'チャンネル名は必須です。',
            'from_date.required' => '有効期限は必須です。',
            'from_time.required' => '有効期限は必須です。',
            'to_date.required' => '有効期限は必須です。',
            'to_time.required' => '有効期限は必須です。',
        ]);

        try {
            $channel_name = 'CN'.str_shuffle(str_random(20).date("YmdHis"));
            $channel_info = $this->twilio_api->createChannel($request->friendly_name, $channel_name);
            $request->channel_sid = $channel_info->sid;
            $this->channel_model->addRecord($request, $channel_name);
        } catch (\Exception $e) {
            $errorcd = 'E5202';
            \Log::error(\Lang::get("errors.{$errorcd}"), [$e]);
        }
        return redirect('/channels');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($channel_name)
    {
        try {
            $userName = $this->auth_info->getOrGenerateAuthUser('email');
            $userId = $this->auth_info->getOrGenerateAuthUser('id');
            $channel = $this->channel_model->getRecordByChannelName($channel_name);
            // 有効期限切れチェック
            if (!$channel) {
                return redirect('/errors/invalid');
            }
            $channelName = $channel->channel_name;
            $friendlyName = $channel->friendly_name;
            $accessToken = $this->twilio_api->getAccessToken($channelName, $userName, $userId);
            $videoToken = $this->twilio_api->getVideoToken($channelName, $userName, $userId);
        } catch (\Exception $e) {
            $errorcd = 'E5201';
            \Log::error(\Lang::get("errors.{$errorcd}"), [$e]);
        }
        return view('channels.show', compact('accessToken', 'videoToken', 'channelName', 'friendlyName', 'userName'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $channel = $this->channel_model->getRecordById($id);
        } catch (\Exception $e) {
            $errorcd = 'E5201';
            \Log::error(\Lang::get("errors.{$errorcd}"), [$e]);
        }
        return view('channels.edit', compact('channel'));
    }

    /**
     * Update the specified resource in local and Twilio storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'friendly_name' => 'bail|required',
            'expires_date' => 'bail|required|future_date',
        ], [
            'friendly_name.required' => 'チャンネル名は必須です。',
            'expires_date.required' => '有効期限は必須です。',
            'expires_date.future_date' => '有効期限を過去の日付にすることはできません。',
        ]);

        try {
            $model = $this->channel_model->getRecordById($id);
            $this->twilio_api->updateChannel($request->friendly_name, $model->channel_sid);
            $this->channel_model->updateRecord($request, $model);
        } catch (\Exception $e) {
            $errorcd = 'E5203';
            \Log::error(\Lang::get("errors.{$errorcd}"), [$e]);
        }
        return redirect('/channels');
    }

    /**
     * Remove the specified resource from local and Twilio storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $model = $this->channel_model->getRecordById($id);
            if (!$model->deleted_flg) {
                $this->twilio_api->deleteChannel($model->channel_sid);
            }
            $this->channel_model->deleteRecord($model);
        } catch (\Exception $e) {
            $errorcd = 'E5204';
            \Log::error(\Lang::get("errors.{$errorcd}"), [$e]);
            return \Lang::get("errors.{$errorcd}");
        }
        return redirect('/channels');
    }

}
