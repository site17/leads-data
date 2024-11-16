import { MutationTree } from "vuex";
import { State } from "./state";
import { MutationTypes } from "../types/mutations";
export type Mutations<S = State> = {
    [MutationTypes.SET_RULER](state: S, ruler: boolean): void;
    [MutationTypes.SET_LOGIN](state: S, login: boolean): void;
};
export const mutations: MutationTree<State> & Mutations = {
    [MutationTypes.SET_RULER](state, ruler) {
        state.isRuler = ruler;
    },
    [MutationTypes.SET_LOGIN](state, login) {
        state.isLOgin = login;
    },
};
