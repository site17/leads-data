// import { IntegrationModel } from "@/entities/leads";
import { onMounted, ref, shallowRef, toRefs, watch } from "vue";

interface Fetch<DataType, Params extends any[]> {
    request: (...args: Params) => Promise<DataType>;
    initialArgs?: Params;
    defaultErrorStatus?: boolean;
    defaultLoadingStatus?: boolean;
    immediate?: boolean;
    onError?: (error: unknown) => void;
    //если критануть ошибку, то сработает onError и все ключи ошибок выставятся в тру
    onSuccess?: (data: DataType) => void;
}

export default function useAsyncState<DataType, Params extends any[] = []>({
    request,
    initialArgs = [] as any,
    defaultErrorStatus = false,
    defaultLoadingStatus = false,
    immediate = true,
    onError = () => {
        return;
    },
    onSuccess = () => {
        return;
    },
}: Fetch<DataType, Params>) {
    const isError = ref<boolean>(defaultErrorStatus);
    const error = ref<string>();
    const isLoading = ref<boolean>(defaultLoadingStatus);
    const data = shallowRef<DataType | undefined>(undefined);
    onMounted(() => {
        if (!immediate) return false;
        console.log("initialArgs", initialArgs);
        executeRequest(...initialArgs);
    });
    function executeRequest(...args: Params) {
        isLoading.value = true;
        isError.value = false;
        request(...args)
            .then((value) => {
                if (
                    typeof value === "object" &&
                    value === data.value &&
                    !Array.isArray(value) &&
                    value !== null
                ) {
                    data.value = { ...value };
                } else {
                    data.value = value;
                }
                onSuccess(data.value);
            })
            .catch((err) => {
                isError.value = true;
                error.value = err.toString
                    ? err.toString()
                    : "Произошла ошибка";
                err;
                console.error(err);
                onError(err);
            })
            .finally(() => {
                isLoading.value = false;
            });
    }
    return {
        isError,
        isLoading,
        error,
        data,
        executeRequest,
    };
}
