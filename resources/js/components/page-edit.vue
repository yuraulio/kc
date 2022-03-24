<style scoped>

</style>

<template>
<div >
    <template v-if="simple">
        <page-edit-simple 
            :pageId="pageId"
            :page="page"
            :content="content"
            @changeMode="changeMode"
            :key="NaN"
        ></page-edit-simple>
    </template>
    <template v-else>
        <pageseditable
            title="true"
            category="true"
            type="edit"
            route="pages"
            page-title="Edit Page"
            :data="page"
            :id="pageId"
            :uuid="null"
            @changeMode="changeMode"
            :key="NaN"
        ></pageseditable>
    </template>
</div> 
</template>

<script>
    import pageseditable from './pageseditable.vue'

    export default {
        components: {
            pageseditable,
        },
        props: {
        },
        data() {
            return {
                pageId: null,
                page: null,
                content: null,
                simple: true,
            }
        },
        methods: {
            getPage() {
                if (this.pageId){
                    axios
                    .get('/api/pages/' + this.pageId)
                    .then((response) => {
                        if (response.status == 200){
                            this.page = response.data.data;
                            this.content = JSON.parse(this.page.content);
                        }
                    })
                    .catch((error) => {
                        console.log(error)
                    });
                }
            },
            changeMode(page) {
                this.page = page;
                this.content = JSON.parse(this.page.content);
                this.simple = !this.simple;
            }
        },
        mounted() {
            this.pageId = this.$attrs.id;
            this.getPage();
        }
    }
</script>
