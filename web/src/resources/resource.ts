import { z } from 'zod';

export const resourceSchema = z.object({
  id: z.number(),
  created_at: z.string().optional().nullable(),
  updated_at: z.string().optional().nullable(),
});

export type Resource = z.infer<typeof resourceSchema>;
