<template>
    <section>
        <div class="container mx-auto">
            <div
                class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0"
            >
                <div
                    class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700"
                >
                    <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                        <h1
                            class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white"
                        >
                            Войти в аккаунт
                        </h1>
                        <form
                            class="space-y-4 md:space-y-6"
                            @submit.prevent="submitAuth"
                        >
                            <div>
                                <label
                                    for="email"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                    >Email</label
                                >
                                <input
                                    v-model="userEmail"
                                    type="email"
                                    name="email"
                                    id="email"
                                    class="input-primary"
                                    placeholder="name@company.com"
                                />
                            </div>
                            <div>
                                <label
                                    for="password"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                    >Пароль</label
                                >
                                <input
                                    v-model="userPassword"
                                    type="password"
                                    name="password"
                                    id="password"
                                    placeholder="••••••••"
                                    class="input-primary"
                                />
                            </div>
                            <div class="primary-btn pink-one"></div>
                            <button type="submit" class="btn-primary">
                                Войти
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script setup lang="ts">
import { computed, onMounted, ref, watch } from "vue";
import useAsyncState from "@/shared/composables/useAsyncState";
import { UsersModel } from "@/entities/user/model";
import { useUserStore } from "@/entities/user/store";
const userEmail = ref("");
const userPassword = ref("");
const emit = defineEmits(["statusAuth"]);

const submitAuth = async (event: any) => {
    event.preventDefault();

    try {
        // Пример корректной обработки ответа http://127.0.0.1:8000
        const response = await fetch(`http://127.0.0.1:8000/api/login`, {
            method: "POST",
            headers: {
                Authorization: `Bearer ${localStorage.getItem("authToken")}`,
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                email: userEmail.value,
                password: userPassword.value,
            }),
        });
        const result = await response.json();
        // Проверка, соответствует ли результат схеме SUserAuth
        localStorage.setItem("authToken", result.token);
        useUserStore().statusAuth();
    } catch (error) {
        console.error("Ошибка при выполнении запроса:", error);
        throw error;
    }
};
</script>
