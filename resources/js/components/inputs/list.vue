<style scoped>
.input {
    width: 80%;
    display: inline-block;
}
</style>

<template>
    <div class="mb-3">
        <h5>{{ title }}</h5>
        <ul class="list-group list-group-flush">
            <li v-for="(item, index) in list" class="list-group-item ps-0 pe-0">
                <div v-if="item.edit == true">
                    <input type="text" v-model="item.title" class="form-control input" />
                    <a
                        @click="edit(index, item.title, item.id)"
                        href="javascript:void(0);"
                        class="action-icon float-end"
                    >
                        <i v-if="!loading" class="mdi mdi-check"></i>
                        <i v-else class="fas fa-spinner fa-spin"></i>
                    </a>
                    <a @click="closeEdit(index)" href="javascript:void(0);" class="action-icon float-end">
                        <i class="mdi mdi-cancel"></i>
                    </a>
                </div>
                <div v-else>
                    {{ item.title }}
                    <a @click="openEdit(index)" href="javascript:void(0);" class="action-icon float-end">
                        <i class="mdi mdi-square-edit-outline"></i>
                    </a>
                    <a @click="remove(item.id)" href="javascript:void(0);" class="action-icon float-end">
                        <i class="mdi mdi-delete"></i>
                    </a>
                </div>
            </li>
            <li class="list-group-item ps-0 pe-0">
                <input type="text" v-model="new_item" :placeholder="placeholder" class="form-control input" />
                <a @click="add()" href="javascript:void(0);" class="action-icon float-end">
                    <i v-if="!loading" class="mdi mdi-check"></i>
                    <i v-else class="fas fa-spinner fa-spin"></i>
                </a>
            </li>
        </ul>

        <div v-if="errors" class="row mt-3">
            <span class="text-danger" v-for="error in errors">{{ error[0] }}</span>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: String,
        propValue: {},
        required: false,
        route: String,
        id: Number,
        placeholder: String,
    },
    data() {
        return {
            list: [],
            new_item: '',
            loading: false,
            errors: null,
        };
    },
    watch: {
        propValue: function () {
            this.list = this.propValue;
        },
    },
    methods: {
        remove(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                showLoaderOnConfirm: true,
                buttonsStyling: false,
                customClass: {
                    cancelButton: 'btn btn-soft-secondary',
                    confirmButton: 'btn btn-soft-danger',
                },
                preConfirm: () => {
                    return axios
                        .delete('/api/' + this.route + '/' + id)
                        .then((response) => {
                            if (response.status == 200) {
                                this.list.splice(_.findIndex(this.list, { id: id }), 1);
                                this.$emit('refresh');
                            }
                        })
                        .catch((error) => {
                            Swal.showValidationMessage(`Request failed: ${error}`);
                        });
                },
                allowOutsideClick: () => !Swal.isLoading(),
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Deleted!', 'Item has been deleted.', 'success');
                }
            });
        },
        add() {
            this.loading = true;
            axios
                .post('/api/' + this.route, {
                    title: this.new_item,
                    parent_id: this.id,
                })
                .then((response) => {
                    if (response.status == 201) {
                        this.list.push(response.data.data);
                        this.$toast.success('Created Successfully!');
                        this.$emit('refresh');
                    }
                    this.loading = false;
                    this.new_item = '';
                    this.errors = null;
                })
                .catch((error) => {
                    console.log(error);
                    this.errors = error.response.data.errors;
                    this.loading = false;
                });
        },
        openEdit(index) {
            this.$set(this.list[index], 'edit', true);
        },
        closeEdit(index) {
            this.list[index].edit = false;
        },
        edit(index, value, id) {
            this.loading = true;
            axios
                .patch('/api/' + this.route + '/' + id, {
                    title: value,
                })
                .then((response) => {
                    if (response.status == 200) {
                        this.$toast.success('Edited Successfully!');
                        this.loading = false;
                        this.list[index].edit = false;
                        this.$emit('refresh');
                    }
                })
                .catch((error) => {
                    console.log(error);
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
        },
    },
    mounted() {},
};
</script>
