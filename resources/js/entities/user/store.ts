import { defineStore } from "pinia";
import { User} from "./types";
import { UsersModel } from "./model";

export const useUserStore = defineStore("users", {
    state: () => ({
        // users: undefined as undefined | Array<Users>,
        selectedUserId: undefined as undefined | number,
        loading: false as boolean,
        error: null as any,
        isAuthenticated: false as boolean,
        token: null as null | string,
        emailUser: null as null | string,
        userInfo: undefined as undefined | User,
    }),
    getters: {
        // getAllStat: (state) => {
        //     console.log("state.users", state.users);
        //     return state.users;
        // },
    },
    actions: {
        async fetchUser() {
            this.loading = true;
            this.statusAuth();
        },
    
        statusAuth() {
            const isTokenExists = localStorage.getItem("authToken");
            if (isTokenExists) {
                this.isAuthenticated = true;
                this.token = localStorage.getItem("authToken");
            } else {
                this.isAuthenticated = false;
            }
        },
        logOut() {
            this.isAuthenticated = false;
            this.token = null;
            localStorage.removeItem("authToken");
        },
       
        async init(): Promise<boolean> {
            try {
                await Promise.all([this.fetchUser()]);
                console.log("init user");
                return true;
            } catch (error) {
                return false;
            }
        },
    },
});
function get(arg0: { url: string }) {
    throw new Error("Function not implemented.");
}
