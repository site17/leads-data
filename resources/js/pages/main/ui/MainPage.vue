<template>
    <log-in-form v-if="!isAuthUser"></log-in-form>
    main page
</template>
<script setup lang="ts">
import { computed, defineComponent, onMounted, ref, watch } from "vue";
import { LogInForm } from "@/widgets/LogInForm";
import { useUserStore } from "@/entities/user/store";
const userStore = useUserStore();
const isAuthUser = computed(() => userStore.isAuthenticated);

onMounted(() => {
    console.log("isAuthUser", isAuthUser.value);
});
</script>
