<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Models\Channel;
use App\MyLibs\TwilioApiManager;

class CheckExpiredChannels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all channels expired then delete them from Twilio storage.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
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
