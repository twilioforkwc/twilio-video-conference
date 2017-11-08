<?php

namespace App\MyLibs;

use Twilio\Rest\Client;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\ChatGrant;
use Twilio\Jwt\Grants\VideoGrant;
use App\MyLibs\AuthInfoManager;

class TwilioApiManager
{
    protected $client;
    protected $twilio_account_sid;
    protected $twilio_account_token;
    protected $twilio_api_key;
    protected $twilio_api_secret;
    protected $auth_info;

    /**
     * [__construct TwilioSDKを利用するための各種情報を設定]
     */
    public function __construct()
    {
        $this->twilio_account_sid = \Config::get('services.twilio.account_sid');
        $this->twilio_account_token = \Config::get('services.twilio.account_token');
        $this->twilio_api_key = \Config::get('services.twilio.api_key');
        $this->twilio_api_secret = \Config::get('services.twilio.api_secret');
        $this->client = new Client($this->twilio_account_sid, $this->twilio_account_token);
        $this->auth_info = new AuthInfoManager();
    }

    /**
     * [getChannels チャンネル一覧を取得します]
     * @return [none] [description]
     */
    public function getChannels()
    {
        $channels = $this->client->chat
            ->services(\Config::get('services.twilio.service_sid'))
            ->channels
            ->read();
    }

    /**
     * [getAccessToken Twilioチャット用のアクセストークンを生成、返却します]
     * @param  [string] $channel [一意のチャンネル名を指定します]
     * @return [string]          [アクセストークンを返却します]
     */
    public function getAccessToken($channel, $user_name, $user_id)
    {
        // Required for Chat grant
        $chatServiceSid = \Config::get('services.twilio.service_sid');
        // An identifier for your app - can be anything you'd like
        $appName = $channel;
        // choose a random username for the connecting user
        $identity = $user_name;
        // A device ID should be passed as a query string parameter to this script
        $deviceId = $user_id;
        $endpointId = $appName . ':' . $identity . ':' . $deviceId;

        // Create access token, which we will serialize and send to the client
        $token = new AccessToken(
            $this->twilio_account_sid,
            $this->twilio_api_key,
            $this->twilio_api_secret,
            3600,
            $identity
        );

        // Create Chat grant
        $chatGrant = new ChatGrant();
        $chatGrant->setServiceSid($chatServiceSid);
        $chatGrant->setEndpointId($endpointId);

        // Add grant to token
        $token->addGrant($chatGrant);

        return $token->toJWT();
    }

    /**
     * [getVideoToken Twilioビデオ用のアクセストークンを生成、返却します]
     * @param  [string] $room [任意のルーム名を指定します]
     * @return [string]       [アクセストークンを返却します]
     */
    public function getVideoToken($room, $user_name, $user_id)
    {
        // Required for Chat grant
        $videoServiceSid = \Config::get('services.twilio.service_sid');
        // An identifier for your app - can be anything you'd like
        $appName = $room;
        // choose a random username for the connecting user
        $identity = $user_name;
        // A device ID should be passed as a query string parameter to this script
        $deviceId = $user_id;
        $endpointId = $appName . ':' . $identity . ':' . $deviceId;

        // Create access token, which we will serialize and send to the client
        $token = new AccessToken(
            $this->twilio_account_sid,
            $this->twilio_api_key,
            $this->twilio_api_secret,
            3600,
            $identity
        );

        // Create Video grant
        $videoGrant = new VideoGrant();

        // Add grant to token
        $token->addGrant($videoGrant);

        return $token->toJWT();
    }

    /**
     * [retrieveChannel 既存チャンネルを取得し、チャンネル情報を返却します]
     * @param  [string] $channel_sid [一意のチャンネル名を指定します]
     * @return [object]              [channelオブジェクトを返却します]
     */
    public function retrieveChannel($channel_sid)
    {
        return $this->client->chat
                ->services(\Config::get('services.twilio.service_sid'))
                ->channels($channel_sid)
                ->fetch();
    }

    /**
     * [createChannel 新規チャンネルを作成し、チャンネル情報を返却します]
     * @param  [string] $friendly_name [任意のチャンネル名を指定します]
     * @param  [string] $channel_name  [一意のチャンネル名を指定します]
     * @return [object]                [channelオブジェクトを返却します]
     */
    public function createChannel($friendly_name, $channel_name)
    {
        return $this->client->chat
                ->services(\Config::get('services.twilio.service_sid'))
                ->channels
                ->create(
                    [
                        'friendlyName' => $friendly_name,
                        'uniqueName' => $channel_name
                    ]
                );
    }

    /**
     * [updateChannel 既存チャンネルのフレンドリーネームを変更し、チャンネル情報を返却します]
     * @param  [string] $friendly_name [任意のチャンネル名を指定します]
     * @param  [string] $channel_sid   [一意のチャンネルSIDを指定します]
     * @return [object]                [channelオブジェクトを返却します]
     */
    public function updateChannel($friendly_name, $channel_sid)
    {
        return $this->client->chat
                ->services(\Config::get('services.twilio.service_sid'))
                ->channels($channel_sid)
                ->update(["friendlyName" => $friendly_name]);
    }

    /**
     * [deleteChannel 既存チャンネルを削除します]
     * @param  [string] $channel_sid   [一意のチャンネルSIDを指定します]
     * @return [boolean]               [削除結果を真偽値で返却します]
     */
    public function deleteChannel($channel_sid)
    {
        return $this->client->chat
                ->services(\Config::get('services.twilio.service_sid'))
                ->channels($channel_sid)
                ->delete();
    }

}
