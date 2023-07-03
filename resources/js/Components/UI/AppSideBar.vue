<template>
    <div class="page-content__sidebar">
        <div class="page-content__sidebar-list justify-start pt-5 h-full">
            <slot/>
        </div>

        <div class="page-content__sidebar-separator"/>

        <ul class="page-content__sidebar-list">
            <li class="page-content__sidebar-list-item" title="Change locale">
                <language-selector/>
            </li>
        </ul>

        <div class="page-content__sidebar-separator"/>

        <ul class="page-content__sidebar-list">
            <inertia-link :href="route('profile.show')" class="page-content__sidebar-list-item" title="Profile">
                <svg-vue class="page-content__sidebar-list-item-icon" icon="nav/settings"/>
                <span class="page-content__sidebar-list-item-label">{{ $t("profile") }}</span>
            </inertia-link>
            <li class="page-content__sidebar-list-item" title="Logout">
                <form @submit.prevent="logout">
                    <button class="flex outline-none items-center" type="submit">
                        <svg-vue class="page-content__sidebar-list-item-icon" icon="nav/logout"/>
                        <span class="page-content__sidebar-list-item-label">{{ $t("logout") }}</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</template>

<script>
import axios from 'axios';
import LanguageSelector from "@/Shared/LanguageSelector";

export default {
    components: {
        LanguageSelector
    },
    methods: {
        logout() {
            axios.post(route("logout").url()).then(() => {
                window.location = "/";
            });
        },
    },
};
</script>
