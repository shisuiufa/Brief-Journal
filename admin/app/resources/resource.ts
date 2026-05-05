import { z } from 'zod';

export const resourceSchema = z.object({
    id: z.number().optional(),
    createdAt: z.string().optional(),
    updatedAt: z.string().optional(),
});