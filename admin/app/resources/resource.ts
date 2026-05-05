import { z } from 'zod';

export const resourceSchema = z.object({
    id: z.number(),
    createdAt: z.string().optional(),
    updatedAt: z.string().optional(),
});