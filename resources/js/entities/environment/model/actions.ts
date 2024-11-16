import { Mutations } from "./mutations";
import { ActionTree, ActionContext } from "vuex";
import { State } from "./state";
import { ActionTypes } from "../types/actions";
import { RootState } from "@/entities/store";
import { MutationTypes } from "../types/mutations";
// import { PageFonts } from "@/types";
// import { CodeInterface } from "@/types";

type AugmentedActionContext = {
    commit<K extends keyof Mutations>(
        key: K,
        payload: Parameters<Mutations[K]>[1]
    ): ReturnType<Mutations[K]>;
} & Omit<ActionContext<State, RootState>, "commit">;

export interface Actions {
    [ActionTypes.PUT_RULER]({ commit }: AugmentedActionContext): void;
    [ActionTypes.REMOVE_RULER]({ commit }: AugmentedActionContext): void;
    [ActionTypes.PUT_LOGIN]({ commit }: AugmentedActionContext): void;
}

export const actions: ActionTree<State, RootState> & Actions = {
    [ActionTypes.PUT_RULER]({ commit }) {
        commit(MutationTypes.SET_RULER, true);
    },
    [ActionTypes.REMOVE_RULER]({ commit }) {
        commit(MutationTypes.SET_RULER, false);
    },
    [ActionTypes.PUT_LOGIN]({ commit }) {
        commit(MutationTypes.SET_LOGIN, true);
    },
};
