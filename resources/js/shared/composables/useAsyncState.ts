// import { IntegrationModel } from "@/entities/integrations";
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

        executeRequest(...initialArgs);
    });
    function executeRequest(...args: Params) {
        isLoading.value = true;
        isError.value = false;

        request(...args)
            .then((value) => {
                console.log("data.value 1%", value);
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
                console.log("data.value %", data.value);
            })
            .catch((err) => {
                // const arrErrors = Object.values(err.response.data.errors);
                // error.value = err.toString ? arrErrors[0] : "Произошла ошибка";
                isError.value = true;
                console.log("Object.values(err)", Object.values(err));

                // const arrErrors = Object.values(err.response.data.errors).join(
                //     " "
                // );
                // error.value = arrErrors ? arrErrors : "Произошла ошибка";
                error.value = "Произошла ошибка";
                // err;
                // Object.values(err.response.data.errors);

                // console.error("err", Object.values(err.response.data.errors));
                onError(error);
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
