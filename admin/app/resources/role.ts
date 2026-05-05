import { z } from 'zod';

export const roleSchema = z.enum([
    'super_admin',
    'admin',
    'editor',
    'user',
])