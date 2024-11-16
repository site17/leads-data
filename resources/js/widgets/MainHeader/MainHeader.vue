<template>
    <header>
        <div class="container mx-auto">
            <div class="flex column-header">
                <div class="profile-wrp flex">
                    <div class="profile-img">
                        <img src="@/assets/profile.jpg" />
                    </div>
                    <div class="profile-info">
                        <div class="name">{{ userInfo?.name }}</div>
                        <div class="project">
                            проект {{ userInfo?.project }}
                        </div>
                    </div>
                </div>
                <div class="button-column">
                    <button class="btn-login" @click="logOut">Выйти</button>
                </div>
            </div>
        </div>
    </header>
</template>
<script setup lang="ts">
import { computed, onMounted, ref, watch } from "vue";
import useAsyncState from "@/shared/composables/useAsyncState";
import { UsersModel } from "@/entities/user/model";
import { useUserStore } from "@/entities/user/store";
import { storeToRefs } from "pinia";
const logOut = () => {
    useUserStore().logOut();
};

const { userInfo } = storeToRefs(useUserStore());
</script>
<style scoped type="scss">
header {
    height: 85px;
    box-shadow: 6px 0 32px 0 rgba(0, 0, 0, 0.1);
    background: #fff;
    padding: 25px 0;
}
.profile-wrp {
    width: 25%;
    max-width: 150px;
}
.profile-img {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 6px;
}
.profile-img img {
    max-width: 100%;
}
.profile-info {
    display: flex;
    flex-flow: column;
    justify-content: center;
}
.profile-info .name {
    font: 400 12px/1 Arial;
    color: #000;
}
.profile-info .project {
    font: italic 400 8px/1 Arial;
    color: rgba(71, 71, 102, 0.75);
}
.column-header {
    justify-content: space-between;
}
.btn-login {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    border-radius: 9px;
    padding: 0 10px;
    max-width: 70px;
    height: 31px;
    background: rgba(206, 209, 217, 0.5);
    font: 400 10px Arial;
    color: #000;
}
.btn-login:hover {
    background: #ced1d9;
}
.button-column {
    width: 70px;
}
@media (max-width: 768px) {
    .profile-wrp {
        width: 50%;
        max-width: 120px;
    }
}
</style>
