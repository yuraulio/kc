<style scoped>

</style>

<template>
    <div>

        <div class="page-title-box mt-3 mb-3">
            <h4 class="page-title">Pages</h4>
        </div>

        <paginationtable :config="config"></paginationtable>

    </div>
</template>
<script>
import Paginationtable from './table/paginationtable.vue';
import pagesConfig from "../table_configs/pagesTable";

    export default {
        components: {
            pagesConfig,
            Paginationtable,
            // multidropdown,
        },
        props: {

        },
        data() {
            return {
                config: pagesConfig,


                // mode: "list",
                // pages: [],
                // id: null,
                // uuid: null,
                // title: null,
                // filter: "",
                // isLoading: false,
                // additionalTemplates: [],
                // lodash: _,
                // type_value: null,
                // category_value: null,
                // subcategory_value: null,
                // type_list: [
                //     {
                //         'id': 1,
                //         'title':'Article'
                //     },
                //     {
                //         'id': 2,
                //         'title':'Blog'
                //     },
                //     {
                //         'id': 3,
                //         'title':'Course page'
                //     },
                //     {
                //         'id': 4,
                //         'title':'Trainer page'
                //     },
                //     {
                //         'id': 5,
                //         'title':'General'
                //     }
                // ],
                // subcategories: [],
                // published_value: null,
                // order: true,
                // dynamic: {
                //     title: 'Static pages',
                //     id: false
                // },
            }
        },
        watch: {
            filter: function() {
                this.getData();
            },
            "category_value": function() {

            }
        },
        methods: {
            
        },
        mounted() {
            
        }
    }
</script>

<style scoped>

</style>
