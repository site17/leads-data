import { GetterTree } from "vuex";
import { State, state } from "./state";
import { RootState } from "@/entities/store";

export type Getters = {
    isRuler(state: State): boolean;
    isLogin(state: State): boolean;
};
export const getters: GetterTree<State, RootState> & Getters = {
    isRuler: (state) => state.isRuler,
    isLogin: (state) => state.isLogin,
};
