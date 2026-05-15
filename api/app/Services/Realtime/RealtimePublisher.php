<?php

namespace App\Services\Realtime;

use App\Contracts\Realtime\RealtimePublisherInterface;
use App\Enums\Realtime\RealtimeEventEnum;
use Illuminate\Support\Facades\Redis;
use Throwable;

final readonly class RealtimePublisher implements RealtimePublisherInterface
{
    private const string CHANNEL = 'realtime-events';

    /**
     * @throws Throwable
     */
    public function publish(RealtimeEventEnum $event, array $payload = []): void
    {
        Redis::connection('realtime')->command('publish', [
            self::CHANNEL,
            json_encode([
                'event' => $event->value,
                'payload' => $payload,
            ]),
        ]);
    }
}
