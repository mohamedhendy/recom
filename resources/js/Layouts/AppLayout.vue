<template>
    <div class="app-layout">
        <nav class="page-header fixed w-full z-50">
            <div class="page-header__container">
                <div class="flex items-center gap-5 justify-center">
                    <inertia-link class="page-header__logo" :href="route('sale_purchase_orders.index')">
                        <h1 class="page-header__logo-title">
                            {{ $App.title }}
                        </h1>
                        <span class="page-header__logo-subtitle">{{ $App.slogan }}</span>
                    </inertia-link>

                    <BackButton/>
                </div>
                <div class="page-header__right-list">
                    <div class="page-header__right-list page-header__right-list-buttons-area">
                        <slot name="screen-actions"/>
                    </div>
                </div>
            </div>
        </nav>

        <PageContent>
            <Sidebar ref="sideBar">
                <inertia-link v-for="menuItem in $menuConfig" :key="menuItem.id" class="page-content__sidebar-list-item"
                              :href="menuItem.url"
                >
                    <svg-vue class="page-content__sidebar-list-item-icon" :icon="menuItem.icon"/>
                    <span class="page-content__sidebar-list-item-label">{{ menuItem.label($t) }}</span>
                </inertia-link>

                <inertia-link v-if="$page.props.can.manage_users" class="page-content__sidebar-list-item" href="/users">
                    <svg-vue class="page-content__sidebar-list-item-icon" icon="users"/>
                    <span class="page-content__sidebar-list-item-label">{{ $t("users") }}</span>
                </inertia-link>
            </Sidebar>

            <div class="page-content__body">
                <div class="screen">
                    <header class="screen__header">
                        <slot name="header"/>
                    </header>
                    <main class="screen__body">
                        <slot/>
                    </main>
                </div>
            </div>
        </PageContent>
    </div>
</template>

<script>
import axios from 'axios';
import PageContent from "@/Components/UI/AppPageContent";
import Sidebar from "@/Components/UI/AppSideBar";
import BackButton from "@/Components/UI/BackButton";
import AlertMixin from "@/Mixins/AlertMixin";
import ResponseMixin from "@/Mixins/ResponseMixin";

export default {
    components: {
        PageContent,
        Sidebar,
        BackButton
    },
    mixins: [AlertMixin,ResponseMixin],
    data() {
        return {
            sidebarItems: []
        };
    },
    mounted() {
        this.sidebarItems = document.querySelectorAll('.page-content__sidebar-list-item-icon');
        this.initSidebarEventListeners();
    },
    beforeDestroy() {
        this.deInitSidebarEventListeners();
    },
    methods: {
        logout() {
            axios.post(route("logout").url()).then(() => {
                window.location = "/";
            });
        },
        initSidebarEventListeners() {
            this.$refs.sideBar.$el.addEventListener('mouseleave', this.collapseSidebar);
            this.sidebarItems.forEach($el => {
                $el.addEventListener('mouseenter', this.expandSidebar);
            });
        },
        deInitSidebarEventListeners() {
            this.$refs.sideBar.$el.removeEventListener('mouseleave', this.collapseSidebar);
            this.sidebarItems.forEach($el => {
                $el.removeEventListener('mouseenter', this.expandSidebar);
            });
        },
        expandSidebar() {
            let $sideBar = this.$refs.sideBar.$el;
            if (!$sideBar.classList.contains('page-content__sidebar--expanded')) {
                $sideBar.classList.add('page-content__sidebar--expanded');
            }
        },
        collapseSidebar() {
            this.$refs.sideBar.$el.classList.remove('page-content__sidebar--expanded');
        }
    },

};
</script>
