<?php

namespace App\Channels;
use Illuminate\Notifications\Notification;
use Kavenegar\KavenegarApi;

class SmsChannel
{
    public function send($notifiable,Notification $notification)
    {
        return 'Done!';
        $sender = "2000500666";
        $receptor = $notifiable->cellphone;
        $message = $notification->code;
        $api = new KavenegarApi(env('KAVEH_API')); $api -> Send ( $sender,$receptor,$message);
    }
}
