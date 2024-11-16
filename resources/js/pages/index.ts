import type { RouteRecordRaw } from "vue-router";
import Routing from "./index.vue";
export enum ROUTE_NAMES {
    Main = "Main",
    NotFound = "NotFound",
    Forbidden = "Forbidden",
    leads = "Leads",
    // Stats = "Stats",
}
export const routes: Array<RouteRecordRaw> = [
    {
        path: "/",
        name: ROUTE_NAMES.Main,
        component: () => import("./main/ui/MainPage.vue"),
    },
    {
        path: "/:pathMatch(.*)*",
        name: ROUTE_NAMES.NotFound,
        component: () => import("./errors/404"),
        meta: { name: "Страница не найдена", routeName: ROUTE_NAMES.NotFound },
    },
    {
        path: "/errors/403",
        name: ROUTE_NAMES.Forbidden,
        component: () => import("./errors/403"),
    },
    {
        path: "/leads",
        name: ROUTE_NAMES.leads,
        component: () => import("./leads/LeadsAll.vue"),
        meta: { name: "Все лиды", routeName: ROUTE_NAMES.leads },
    },
    {
        path: "/leads/:id",
        name: "lead",
        component: () => import("./lead/LeadCard.vue"),
    },
];
export { Routing };
