import * as Schemes from "./scheme";
import { z } from "zod";


export type UserData = z.infer<typeof Schemes.SUserData>;
export type UserAuth = z.infer<typeof Schemes.SUserAuth>;
export type User = z.infer<typeof Schemes.SUser>;
