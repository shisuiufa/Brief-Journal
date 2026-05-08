import { z } from 'zod'
import { resourceSchema } from '@/resources/resource'

export const userSchema = resourceSchema.extend({
  name: z.string(),
  email: z.email(),
})

export type UserResource = z.infer<typeof userSchema>
