<template>
    <div class="col-12">
        <div class="card project-box ribbon-box">
            <div class="card-body">
                <div class="ribbon ribbon-success float-end"><i class="mdi mdi-access-point me-1"></i> Published</div>
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
                <h4 class="mt-0"><a href="project-detail.html" class="text-dark">{{ page.title }}</a></h4>
                <p class="text-muted text-uppercase"><i class="mdi mdi-account-circle"></i> <small v-if="page.template">{{ page.template.title }}</small></p>
                <div v-for="cat in page.categories" class="badge bg-info text-white mb-3 mr-2 font-14" style="margin-right: 5px">{{ cat.title }}</div>
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
                        <div class="form-check form-switch mb-1" style="display: inline-block;">
                            <input type="checkbox" class="form-check-input" name="color-scheme-mode" value="light" id="light-mode-check" checked="">
                            <label class="form-check-label" for="light-mode-check">Published</label>
                        </div>
                    </span>
                </p>
            </div>
        </div> <!-- end card box-->
    </div>
</template>

<script>

    export default {
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

            }
        },
        methods: {
            edit(){
                this.$emit('updateid', this.page.id);
                this.$emit('updatetitle', this.page.title);
                this.$emit('updatemode', 'edit', this.page.id);
            },
            remove(){
                this.$emit('updateid', this.page.id);
                this.$emit('updatetitle', this.page.title);
                this.$emit('updatemode', 'edit', this.page.id);
            }
        },
        mounted() {

        }
    }
</script>
