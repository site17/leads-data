import axios, { AxiosRequestConfig, AxiosResponse } from "axios";
import { ZodType } from "zod";
interface ResponseContainer<type> {
    data: type;
}

export class BaseApi {
    protected axiosConfig: AxiosRequestConfig;
    protected pathToData: Array<string>; // путь до ответа валидируемого типа Zod
    //, http://127.0.0.1:8000/ 
    protected constructor() {
        this.axiosConfig = {
            baseURL: `http://127.0.0.1:8000/api`,
            timeout: 10000,

            // headers: { "Content-Type": "application/json" },
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                Authorization: `Bearer ${localStorage.getItem("authToken")}`,
            },
        };
        this.pathToData = ["data"]; //["data"];
    }
    private zodValidate<T>(scheme: ZodType, result: T) {
        const response = scheme.safeParse(result);
        console.log(result, "response zodValidate", response);
        if (response.success === false) {
            throw response;
            // throw new CriticalError({
            //     bundle: "not structure error",
            //     message: "Ошибка валидации типа ответа сервера",
            //     isNotification: false,
            // });
        }
    }
    protected hookBeforeRequest(
        requestConfig: AxiosRequestConfig
    ): AxiosRequestConfig {
        const result = {
            ...this.axiosConfig,
            ...requestConfig,
        };
        return result;
    }
    protected hookAfterResponse<ResponseDataType>(
        response: AxiosResponse<ResponseContainer<ResponseDataType>>,
        validationScheme: ZodType
    ): ResponseDataType {
        const result = this.pathToData.reduce((acc, curr) => {
            // eslint-disable-next-line @typescript-eslint/ban-ts-comment
            // @ts-ignore
            return acc[curr];
        }, response.data) as unknown as ResponseDataType;
        this.zodValidate(validationScheme, result);
        return result;
    }
    protected async get<ResponseDataType>(
        requestConfig: AxiosRequestConfig,
        validationScheme: ZodType
    ): Promise<ResponseDataType> {
        const getConfig = this.hookBeforeRequest(requestConfig);

        const response = await axios.get<ResponseContainer<ResponseDataType>>(
            getConfig.url ?? "",
            getConfig
        );
        return this.hookAfterResponse<ResponseDataType>(
            response,
            validationScheme
        );
    }
    protected async post<ResponseDataType>(
        requestConfig: AxiosRequestConfig,
        validationScheme: ZodType
    ): Promise<ResponseDataType> {
        const postConfig = this.hookBeforeRequest(requestConfig);
        console.log(" postConfig", postConfig);
        const response = await axios.post<ResponseContainer<ResponseDataType>>(
            postConfig.url ?? "",
            postConfig.data,
            postConfig
        );
        console.log(response, "==", validationScheme);
        return this.hookAfterResponse<ResponseDataType>(
            response,
            validationScheme
        );
    }
    protected async put<ResponseDataType>(
        requestConfig: AxiosRequestConfig,
        validationScheme: ZodType
    ): Promise<ResponseDataType> {
        const putConfig = this.hookBeforeRequest(requestConfig);
        const response = await axios.put<ResponseContainer<ResponseDataType>>(
            putConfig.url ?? "",
            putConfig.data,
            putConfig
        );

        return this.hookAfterResponse<ResponseDataType>(
            response,
            validationScheme
        );
    }
    protected async delete<ResponseDataType>(
        requestConfig: AxiosRequestConfig,
        validationScheme: ZodType
    ): Promise<ResponseDataType> {
        const deleteConfig = this.hookBeforeRequest(requestConfig);
        const response = await axios.delete<
            ResponseContainer<ResponseDataType>
        >(deleteConfig.url ?? "", deleteConfig);
        return this.hookAfterResponse<ResponseDataType>(
            response,
            validationScheme
        );
    }
}
