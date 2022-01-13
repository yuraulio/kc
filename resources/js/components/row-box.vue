<style scoped>
    .avatar-height {
        height: 60px;
    }
</style>

<template>
    <div class="card mb-2">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-sm-1">
                    <avatar
                        class="d-flex align-self-center me-3 rounded-circle"
                        :username="title"
                        backgroundColor="#6658dd"
                        color="#fff"
                    >
                    </avatar>
                </div>
                <div class="col-sm-7">
                    <div class="d-flex align-items-start">
                        <div class="w-100">
                            <h4 class="mt-0 mb-2 font-16">{{title}}</h4>
                            <p class="mb-1">{{description}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div v-if="category" class="text-center mt-3 mt-sm-0">
                        <div class="badge font-14 bg-soft-info text-info p-1">{{category}}</div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="text-sm-end">
                        <a @click="edit()" href="javascript:void(0);" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>
                        <a @click="remove()" href="javascript:void(0);" class="action-icon"> <i class="mdi mdi-delete"></i></a>
                    </div>
                </div> <!-- end col-->
            </div> <!-- end row -->
        </div>
    </div> <!-- end card-->
</template>

<script>

    export default {
        props: {
            title: String,
            description: String,
            category: String,
            id: Number,
        },
        data() {
            return {
                
            }
        },
        methods: {
            edit(){
                this.$emit('updateid', this.id);
                this.$emit('updatetitle', this.title);
                this.$emit('updatemode', 'edit');
            },
            remove(){
                this.$emit('updateid', this.id);
                this.$emit('updatetitle', this.title);
                this.$emit('updatemode', 'delete');
            }
        },
        mounted() {
            
        }
    }
</script>
