<?php

namespace Sikhlana\MuthofunSmsChannel;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use Propaganistas\LaravelPhone\PhoneNumber;

class MuthofunChannel
{
    const API_URL = 'http://clients.muthofun.com:8901/esmsgw/sendsms.jsp';

    /**
     * The Guzzle HTTP implementation.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Creates a new Greenweb SMS channel instance.
     *
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('muthofun', $notification)) {
            if (! isset($notifiable->phone_number)) {
                return;
            }

            $to = $notifiable->phone_number;
        }

        $message = $notification->toMuthofun($notifiable);

        if (is_string($message)) {
            $message = new MuthofunMessage($message);
        }

        $to = Arr::wrap($to);

        foreach ($to as &$recipient) {
            $recipient = PhoneNumber::make($recipient, 'BD')->formatNational();
        }

        $config = [
            'headers' => [
                'Accept' => 'application/xml',
            ],
            'query' => [
                'user' => config('services.muthofun.username'),
                'password' => config('services.muthofun.password'),
                'mobiles' => implode(',', $to),
                'sms' => rawurlencode($message->buildMessage()),
            ],
        ];

        if ($message->unicode) {
            $config['query']['unicode'] = 1;
        }

        if ($message->from) {
            $config['query']['senderid'] = $message->from;
        }

        $this->client->get(static::API_URL, $config);
    }
}