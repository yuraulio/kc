<style scoped>

</style>

<template>
    <div>
        <paginationtable :config="config"></paginationtable>

    </div>
</template>

<script>
    import royaltiesConfig from "../table_configs/royaltiesItemTable";
    import Paginationtable from './table/paginationtable.vue';

    export default {
        components: {
            royaltiesConfig,
            Paginationtable,
        },
        props: {
            instructor: Array,
            view: String

        },
        data() {
            return {
                config: royaltiesConfig,
            }
        },
        methods: {
        },
        created() {
            if(this.instructor[0].id){

                this.config.royaltyView = this.view;
                if(this.view == 'single'){

                    this.config.apiUrl = this.config.apiUrl+'/'+this.instructor[0].id
                    this.config.instructor = this.instructor[0]


                }else{


                    this.config.apiUrl = this.config.apiUrl+'/0'
                    this.config.royaltyView = 'single';

                }


            }
        },
        mounted() {
        }
    }
</script>
