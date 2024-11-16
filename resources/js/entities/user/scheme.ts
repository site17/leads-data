import { z } from "zod";


export const SUserData = z.object({
    email: z.string(),
    password: z.string(),
});

export const SUserAuth = z.object({
    data: z.object({
        token: z.string(),
    }),
});

export const SUser = z.object({
    name: z.string().nullable(),
    project: z.string().nullable(),
});
