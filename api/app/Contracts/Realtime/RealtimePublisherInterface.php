<?php

namespace App\Contracts\Realtime;

use App\Enums\Realtime\RealtimeEventEnum;

interface RealtimePublisherInterface
{
    public function publish(RealtimeEventEnum $event, array $payload = []): void;
}
