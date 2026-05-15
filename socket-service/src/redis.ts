import { Redis } from 'ioredis';
import type { Server } from 'socket.io';

const redisHost = process.env.REDIS_HOST || 'redis';
const redisPort = Number(process.env.REDIS_PORT || 6379);
const redisChannel = process.env.REDIS_CHANNEL || 'realtime-events';

type RealtimeEvent = {
    event: string;
    payload: unknown;
};

export const subscribeToRealtimeEvents = (io: Server) => {
    const redisSubscriber = new Redis({
        host: redisHost,
        port: redisPort,
    });

    redisSubscriber.subscribe(redisChannel, (error) => {
        if (error) {
            console.error(`Failed to subscribe to Redis channel "${redisChannel}"`, error);
            return;
        }

        console.log(`Subscribed to Redis channel "${redisChannel}"`);
    });

    redisSubscriber.on('message', (channel, message) => {
        console.log(`Redis message received on "${channel}": ${message}`);

        const event = JSON.parse(message) as RealtimeEvent;

        io.emit(event.event, event.payload);
    });
};