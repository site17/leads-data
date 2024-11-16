<template>
    <log-in-form v-if="!isAuthUser"></log-in-form>
    <main-content v-if="isAuthUser" />
</template>
<script setup lang="ts">
import { computed, defineComponent, onMounted, ref } from "vue";
import { MainContent } from "@/widgets/MainContent";
import { LogInForm } from "@/widgets/LogInForm";
import { useUserStore } from "@/entities/user/store";
const userStore = useUserStore();
const isAuthUser = computed(() => userStore.isAuthenticated);

</script>
