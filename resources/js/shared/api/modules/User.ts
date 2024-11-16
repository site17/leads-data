import { User, UserAuth, UserData } from "@/entities/user/types";
import { BaseApi } from "./BaseApi";
import { z } from "zod";
import { SUser, SUserAuth } from "@/entities/user";

const getResponseDataSchema = z.object({
    admin: z.boolean(),
    tester: z.boolean(),
    block_creator: z.boolean(),
});

export type UserRightsGetResponseData = z.infer<typeof getResponseDataSchema>;

export class ApiUser extends BaseApi {
    private static instance: ApiUser;
    private localBaseUrl = "users/";
    private constructor() {
        super();
    }
    static getInstance() {
        if (!ApiUser.instance) {
            ApiUser.instance = new ApiUser();
            // ... здесь единожды выполняется инициализация ...
        }
        return ApiUser.instance;
    }
    // public async getUserInfo(user_id:number): Promise<UserRightsGetResponseData> {
    public async getUserInfo() {
        const result: User = await this.get(
            {
                url: `/user-info`,
            },
            SUser
        );
        console.log("result", result);
        return result;
    }
    public async authUser(data: UserData): Promise<UserAuth> {
        const result: UserAuth = await this.post(
            {
                url: `http://127.0.0.1:8000/api/login`,
                data,
            },
            SUserAuth
        );
        return result;
    }
}
