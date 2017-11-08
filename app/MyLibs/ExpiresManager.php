<?php

namespace App\MyLibs;

use App\Http\Models\Channel;
use App\MyLibs\TwilioApiManager;

class ExpiresManager
{
    public function checkChannels()
    {
        $twilio_api = new TwilioApiManager();
        $channel_model = new Channel();
        $channel_list = $channel_model->getRecordsExpired();
        foreach ($channel_list as $channel) {
            try {
                $twilio_api->deleteChannel($channel->channel_sid);
                $channel->deleted_flg = true;
                $channel->save();
            } catch (\Exception $e) {
                $errorcd = 'E5201';
                \Log::error(\Lang::get("errors.{$errorcd}"), [$e]);
            }
        }
    }
}
