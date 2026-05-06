import { z } from 'zod';

export const resourceSchema = z.object({
    id: z.number(),
    created_at: z.string().optional(),
    updated_at: z.string().optional(),
});