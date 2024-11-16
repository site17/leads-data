<template>
    <main-header v-if="isAuthUser" />
    <div class="main">
        <Routing />
    </div>
    <main-footer v-if="isAuthUser" />
</template>

<script setup lang="ts">
import { computed, defineComponent, onMounted, ref } from "vue";
import { storeToRefs } from "pinia";
import { MainFooter } from "@/widgets/MainFooter";
import { MainHeader } from "@/widgets/MainHeader";
import { ROUTE_NAMES, Routing } from "@/pages";
import { RouteLocationRaw, useRoute } from "vue-router";
import { useUserStore } from "@/entities/user/store";
const userStore = useUserStore();
const isAuthUser = computed(() => userStore.isAuthenticated);
const { userInfo } = storeToRefs(useUserStore());


onMounted(async () => {
    userStore.init();
});

type Link = {
    routePath: RouteLocationRaw;
    name: string;
};
const links = ref<Array<Link>>([
    {
        routePath: {
            name: ROUTE_NAMES.Main,
        },
        name: "Главная страница",
    },
    {
        routePath: {
            name: ROUTE_NAMES.leads,
        },
        name: "Задачи",
    },
]);
</script>

<style>
@import "./index.scss";
</style>
