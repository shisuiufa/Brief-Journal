import http from 'node:http';

import { Server } from 'socket.io';

const corsOrigins = process.env.CORS_ORIGIN?.split(',') ?? [];

export const createSocketServer = (server: http.Server) => {
    const io = new Server(server, {
        cors: {
            origin: corsOrigins,
        },
    });

    io.on('connection', (socket) => {
        console.log(`Socket connected: ${socket.id}`);

        socket.on('disconnect', () => {
            console.log(`Socket disconnected: ${socket.id}`);
        });
    });

    return io;
};