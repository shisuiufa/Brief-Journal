import 'dotenv/config';
import http from 'node:http';

import { subscribeToRealtimeEvents } from './redis.js';
import { createSocketServer } from './socket.js';

const port = Number(process.env.PORT || 3001);

const server = http.createServer();
const io = createSocketServer(server);

subscribeToRealtimeEvents(io);

server.listen(port, () => {
    console.log(`Socket service listening on port ${port}`);
});