<template>
    <!-- <RouterView v-slot="{ Component, route }"></RouterView> -->
    <RouterView v-slot="{ Component }">
        <!-- TODO Из-за Transition отображается компонент при перезагрузке страницы -->
        <!-- :name="getTransitionName(route.meta)" -->
        <!-- <Transition mode="out-in"> -->
        <!-- main content -->

        <component :is="Component"></component>
        <!-- </Transition> -->
        <!-- <template v-if="isShowLoader">
            <div class="main_centering">
                <v-progress-circular
                    color="primary"
                    indeterminate
                    :size="52"
                    :width="5"
                />
            </div>
        </template> -->
    </RouterView>
</template>
<script setup lang="ts">
import {
    RouteLocationNormalized,
    RouteMeta,
    RouterView,
    useRouter,
} from "vue-router";
import { ref } from "vue";
// import { useUserInfoStore } from "@/entities/user-info";
import { storeToRefs } from "pinia";
// import { setAsyncInterval } from "@/shared/libs/async";
import { ROUTE_NAMES } from ".";
// import { useStatStore } from "@/entities/stat/store";
const router = useRouter();
const isShowLoader = ref(false);

const getTransitionName = (transitionMeta: RouteMeta) => {
    console.log("transitionMeta", transitionMeta);
    if (
        transitionMeta &&
        typeof transitionMeta === "object" &&
        transitionMeta.transition &&
        typeof transitionMeta.transition === "string"
    ) {
        return transitionMeta.transition;
    }
    return "fade";
};
router.beforeEach(async (to) => {
    isShowLoader.value = true;
    if (to.meta?.isStatPath) {
        return await statePathValidate(to);
    }
});

router.afterEach(() => {
    isShowLoader.value = false;
});

const statePathValidate = async (to: RouteLocationNormalized): Promise<any> => {
    // const { selectedStatId, stats } = storeToRefs(useStatStore());
    // const { setSelectedStatId } = useStatStore();
    // if (stats.value === undefined) {
    //     await setAsyncInterval(() => {
    //         return useStatStore().stats !== undefined;
    //     }, 100);
    // }
    // if (stats.value === undefined) return;
    // let statIdParams: string | string[] | number = to.params.statId;
    // //если id не указан или передан массив
    // if (!statIdParams || Array.isArray(statIdParams)) {
    //     const currentStat = selectedStatId.value ?? stats.value[0];
    //     return { ...to, params: { ...to.params, statId: currentStat } };
    // }
    // //parse to number
    // statIdParams = parseInt(statIdParams);
    // //если переданный id не привязан к польователю
    // if (
    //     !stats.value.find((stat) => {
    //         return stat.stat_id == statIdParams;
    //     })
    // ) {
    //     return { name: ROUTE_NAMES.Forbidden };
    // }
    // //Если все в порядке
    // setSelectedStatId(statIdParams);
};
</script>

<style></style>
