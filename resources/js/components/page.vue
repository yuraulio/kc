<style scoped>
.img-align {
    align-items: center;
}
</style>

<template>
    <div class="col-12">
        <div class="card project-box ribbon-box">
            <div class="card-body">
                <div v-if="page.published == true" class="ribbon ribbon-success float-end"><i class="mdi mdi-access-point me-1"></i> Published</div>
                <div v-else class="ribbon ribbon-danger float-end"><i class="mdi mdi-access-point me-1"></i> Not Published</div>
                <!-- <div class="dropdown float-end">
                    <a href="#" class="dropdown-toggle card-drop arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-dots-horizontal m-0 text-muted h3"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" style="">
                        <a class="dropdown-item" href="#">Edit</a>
                        <a class="dropdown-item" href="#">Delete</a>
                    </div>
                </div> --> <!-- end dropdown -->
                <!-- Title-->
                <div class="row">
                    <div class="col-1 d-flex img-align">
                        <img v-if="getMetaImage()" class="" width="100%" :src="getMetaImage()['url']">
                        <img v-else width="100%" src="/admin_assets/images/pg-image.png">
                    </div>
                    <div class="col-11">
                        <h4 class="mt-0">
                            <template v-if="page.type == 'Blog'">
                                <a :href="'/v2/blog/' + page.slug" target="_blank" class="text-dark">{{ page.title }}</a>
                            </template>
                            <template v-else>
                                <a :href="'/v2/' + page.slug" target="_blank" class="text-dark">{{ page.title }}</a>
                            </template>

                        </h4>
                        <div class="text-muted d-flex justify-content-between mb-2">
                            <div v-if="page.template" class="text-uppercase">
                                <i class="mdi mdi-account-circle"></i> 
                                <small>
                                    <a :href="'/templates?id=' + page.template.id + '&title=' + page.template.title + '&preview=' + true">{{ page.template.title }}</a>
                                </small>
                            </div>
                            <div v-if="page.type">
                                Type: 
                                {{ page.type }}
                            </div>
                        </div>
                        <template v-for="(cat, index) in page.categories">
                            <a :href="'/categories?filter=' + cat.title" :key="index">
                                <div class="badge bg-primary text-white mb-3 mr-2 font-14" style="margin-right: 5px">{{ cat.title }}</div>
                            </a>
                        </template>
                        <!-- Desc-->
                        <p v-if="page.description" class="text-muted font-13 mb-3 sp-line-2">{{ page.description }}
                        </p>
                        <!-- Task info-->
                        <p class="mb-0 pb-0">
                            <span class="text-nowrap d-inline-block" style="float: right">
                                <a @click="remove()" href="javascript:void(0);" class="action-icon"> <i class="mdi mdi-delete"></i></a>
                            </span>

                            <span class="pe-2 text-nowrap d-inline-block" style="float: right">
                                <a @click="edit()" href="javascript:void(0);" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>
                            </span>

                            <span class="text-sm-start" style="flat: right">
                                <span v-if="page.published_from">The page will be published automatically {{ page.published_from }}. </span>
                                <span v-if="page.published_to">The page will be unpublished automatically {{ page.published_to }}</span>
                                <div v-if="!page.published_from && !page.published_to" :key="page.id"  class="form-check form-switch mb-1" style="display: inline-block; cursor: pointer">
                                    <input :key="page.id + 'on'" @click="changePublish()" :id="page.id + 'input'" type="checkbox" class="form-check-input" name="color-scheme-mode" value="light" :for="page.id + 'input'" :checked="page.published">
                                    <label class="form-check-label" for="light-mode-check">Publish</label>
                                </div>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div> <!-- end card box-->
    </div>
</template>

<script>


    export default{
        props: {
            page: {},
            description: String,
            category: String,
            id: Number,
            pages: Array,
            user: String,
        },
        data() {
            return {
                loading: false,
            }
        },
        methods: {
            edit(){
                this.$emit('updateid', this.page.id);
                this.$emit('updateuuid', this.page.uuid);
                this.$emit('updatetitle', this.page.title);
                this.$emit('updatemode', 'edit', this.page.id);
            },
            remove(){
                this.$emit('updateid', this.page.id);
                this.$emit('updatetitle', this.page.title);
                this.$emit('updatemode', 'delete', this.page.id);
            },
            changePublish() {
                this.loading = true;
                axios
                .put('/api/pages/update_published/' + this.page.id)
                .then((response) => {
                    if (response.status == 200){
                        this.page.published = !this.page.published;
                        this.$toast.success('Published Status Updated Successfully!')
                    }
                    this.loading = false;
                })
                .catch((error) => {
                    console.log(error)
                    this.loading = false;
                });
            },
            getMetaImage() {
                var image = null;
                var content = JSON.parse(this.page.content);
                content.forEach(function(row, index) {
                    row.columns.forEach(function(column, index2) {
                        if (column.component == "meta") {
                            var index3 = column.template.inputs.findIndex(function(input) {
                                return input.key == "meta_image";
                            });

                            if (column.template.inputs[index3]) {
                                image = column.template.inputs[index3].value;
                            }
                        }
                    });
                });

                return image;
            },
        },
        mounted() {
        }
    }
</script>
