<template>
    <card-lead-content></card-lead-content>
</template>
<script lang="ts">
import { defineComponent } from "vue";

import { CardLeadContent } from "@/widgets/CardLead";
export default defineComponent({
    name: "MainPage",
    components: { CardLeadContent },
});
</script>
