import { z } from "zod";

export const updateProfileSchema = z.object({
  name: z.string().min(3, "Name must be at least 3 characters."),
  email: z.email("Please enter a valid email address."),
});

export const updateProfilePasswordSchema = z
  .object({
    currentPassword: z.string().min(1, "Current password is required."),
    password: z.string().min(8, "Password must be at least 8 characters."),
    passwordConfirmation: z.string().min(8, "Please confirm password."),
  })
  .refine((data) => data.password === data.passwordConfirmation, {
    message: "Passwords do not match.",
    path: ["passwordConfirmation"],
  });

export type UpdateProfileCredentials = z.infer<typeof updateProfileSchema>;
export type UpdateProfilePasswordCredentials = z.infer<
  typeof updateProfilePasswordSchema
>;
