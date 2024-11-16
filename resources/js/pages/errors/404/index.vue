<template>
    <div class="centering-container">
        <article class="not-found">
            <div class="not-found__image-text">
                <div class="not-found__image-text__text">
                    <h1 class="not-found__image-text__text__404">404</h1>
                    <p class="not-found__image-text__text__description">
                        Что-то пошло не так<br />Страница не найдена
                    </p>
                </div>
            </div>
            <div class="not-found__btns">
                <v-btn
                    class="not-found__btns__back text-button"
                    text="Вернуться назад"
                    size="large"
                    color="primary"
                    @click="router.go(-1)"
                />
                <v-btn
                    class="not-found__btns__home text-button"
                    text="На главную"
                    size="large"
                    color="secondary"
                    @click="router.push({ name: ROUTE_NAMES.Main })"
                />
            </div>
        </article>
    </div>
</template>
<script setup lang="ts">
import { useRouter } from "vue-router";
import { ROUTE_NAMES } from "../../";
const router = useRouter();
</script>

<style scoped>
.not-found__image-text {
    display: flex;
    flex-direction: column;
    align-items: start;
    width: 750px;
    height: 470px;
    background-image: url("/public/404.png");
    background-position: bottom right;
    background-size: 76%;
}
.not-found__image-text__text {
    text-align: center;
}
.not-found__image-text__text__404 {
    font-size: 3rem;
}
.not-found__image-text__text__description {
    font-size: 1.5rem;
}
.not-found__btns {
    display: flex;
    justify-content: right;
    gap: 1rem;
    width: 100%;
    margin-top: 2rem;
}
</style>
