<?php

namespace App\Services;

use Ably\AblyRest;

class AblyService
{
    protected $ably;

    public function __construct()
    {
        $this->ably = new AblyRest(env('KjUHEw.g4QvYw:6WIXqjibViuRbYbm-2-ZoLidx7EBnWaOd-6dXxCpDak'));
    }

    public function publish(string $channelName, string $event, array $data)
    {
        $channel = $this->ably->channel($channelName);
        return $channel->publish($event, $data);
    }
}
