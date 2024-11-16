import { EnvironmentStore, State as EnvironmentState } from "./environment";

export type RootState = {
    environment: EnvironmentState;
};
// type Store = StructureStore<Pick<RootState, "structure">>;
export type Store = EnvironmentStore<Pick<RootState, "environment">>;
