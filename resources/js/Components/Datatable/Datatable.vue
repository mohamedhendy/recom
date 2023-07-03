<template>
    <div class="datatable">
        <div class="datatable__container">
            <table ref="dataTable" class="datatable__table">
                <thead>
                    <tr>
                        <slot name="columns"/>
                    </tr>
                </thead>

                <tbody class="datatable__body">
                    <tr
                        v-for="(row, index) in rows"
                        :key="index"
                        class="datatable__row"
                    >
                        <slot v-bind="row" name="row"/>
                    </tr>
                </tbody>
            </table>
        </div>
        <DatatablePagination
            :endpoint="endpoint"
            :links="links"
            :meta="meta"
            @changePage="changePage"
        />
    </div>
</template>

<script>
import axios from 'axios';
import DatatablePagination from "@/Components/Datatable/DatatablePagination";

const headerOffset = 120;

function tableFixHead(e) {
    if (this.$refs.dataTable) {
        const el = e.target.documentElement,
              sT = el.scrollTop;
        this.$refs.dataTable.querySelectorAll("thead th").forEach((th) => {
            if (sT > headerOffset) {
                th.style.transform = `translateY(${sT - headerOffset}px)`;
            } else {
                th.style.transform = `translateY(0px)`;
            }
        });
    }
}

export default {
    name: "Datatable",
    components: {DatatablePagination},
    props: {
        endpoint: {
            type: String,
            required: true,
        },
        queryParams: {
            type: String,
            default: '{ "orderDirection": "desc", "orderBy": "id" }',
        },
    },
    data() {
        return {
            active_page: 1,
            rows: [],
            links: {
                first: null,
                last: null,
                prev: null,
                next: null,
            },
            meta: {
                current_page: null,
                from: null,
                last_page: null,
            },
            url: "",
        };
    },
    watch: {
        queryParams() {
            this.defaultUrl();
            this.fetchData();
        },
    },
    created() {
        this.defaultUrl();
        this.fetchData();
    },
    mounted() {
        // document.addEventListener("scroll", tableFixHead.bind(this));
    },
    methods: {
        defaultUrl() {
            this.url = this.endpoint + "?page=" + this.active_page;
        },
        fetchData(usePagination = false) {
            this.$loading.show({delay: 0, background: "#444"});

            let params = JSON.parse(this.queryParams ? this.queryParams : "{}");
            if (usePagination) {
                delete params["page"];
            }

            axios
                .get(this.url, {
                    params: params,
                })
                .then((res) => {
                    this.rows = res.data.data;
                    this.links = res.data.links;
                    this.meta = res.data.meta;
                    this.active_page = res.data.meta.current_page;
                    this.$emit("metaHasBeenUpdated", {meta: this.meta});
                })
                .catch(() => {
                })
                .finally(() => {
                    this.$loading.hide();
                });
        },
        changePage(e) {
            this.url = e.url;
            this.fetchData(true);
        },
    },
};
</script>
