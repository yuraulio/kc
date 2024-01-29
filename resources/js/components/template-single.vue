<style scoped></style>

<template>
    <div>
        <template v-if="mode == 'new'">
            <editable title="true" type="new" route="templates" page-title="New Template" :id="templateId"></editable>
        </template>
        <template v-if="mode == 'edit'">
            <template v-if="template">
                <editable
                    title="true"
                    type="edit"
                    route="templates"
                    :page-title="template.title"
                    :predata="content"
                    :id="templateId"
                ></editable>
            </template>
            <div v-else class="col-lg-12 text-center">
                <vue-loaders-ball-grid-beat color="#6658dd" scale="1" class="mt-4 text-center">
                </vue-loaders-ball-grid-beat>
            </div>
        </template>
    </div>
</template>

<script>
export default {
    components: {},
    props: {
        mode: String,
        templateId: Number,
    },
    data() {
        return {
            template: null,
            content: null,
        };
    },
    methods: {
        getTemplate() {
            if (this.templateId) {
                axios
                    .get('/api/templates/' + this.templateId)
                    .then((response) => {
                        if (response.status == 200) {
                            this.template = response.data.data;
                            this.content = JSON.parse(this.template.rows);
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
    },
    mounted() {
        if (this.mode == 'edit') {
            this.getTemplate();
        }
    },
};
</script>
