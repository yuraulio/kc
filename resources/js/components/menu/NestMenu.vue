<template>
    <div class="nest-menu">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="create-btn col-md-12">
                       <button
                           v-on:click="showAddMenuForm"
                           class="btn btn-success mat-raised-button"
                           data-target="#addMenuModal">Add Menu</button>
                     </div>
                     <div class="col-md-12">
                        <div class="alert alert-info mt-2">
                             To use a menu on your site just call <code>menu('name')</code> Or <code> @menu('name')</code>
                        </div>
                     </div>
                    <div class="col-md-12 col-sm-12 dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="table-responsive">
                            <table class="table" id="menuTable" >
                                <thead>
                                    <tr>
                                        <th width="120">Id</th>
                                        <th> Name </th>
                                        <th class="action-head text-right" >Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="menu in menus" :key="menu.id">
                                        <td>{{ menu.id }}</td>
                                        <td>{{ menu.name }}</td>
                                        <td class="action-buttons text-right">
                                            <a :href="prefix+'/menus/builder/'+menu.id" class="btn  btn-soft-primary" title="menu build"><i class="material-icons"></i>Builder</a>
                                            <button
                                                class="btn btn-soft-info edit-info"
                                                title="edit menu"
                                                data-target="#editMenuModal"
                                                v-on:click="showEditMenuForm(menu.id)"
                                                :data-id="menu.id">
                                                Edit
                                            </button>
                                            <button
                                                class="btn btn-soft-warning"
                                                title="clone menu"
                                                v-on:click="cloneMenu(menu.id)"
                                                :data-id="menu.id">
                                                Clone
                                            </button>
                                            <button
                                                class="btn btn-soft-danger cs-danger"
                                                title="delete menu"
                                                v-on:click="deleteMenu(menu.id)"
                                                :data-id="menu.id">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modals -->
        <menu-modals :menu="menu" :errors="errors" :update-menu="updateMenu" :add-menu="addMenu" />
    </div>
</template>

<script>
    import menuModals from './MenuModals'
    export default {
        props: ['prefix'],
        components: {
            'menu-modals': menuModals
        },
        data(){
            return {
                menus: [],
                menu: {},
                errors: {
                    name: ""
                },
                successMsg: '',
                settings: {
                    depth: 1
                }
            };
        },
        methods: {
            fetchMenus: function(){
                let self = this;
                let url = this.prefix+'/getMenus';

                axios({
                    url: url,
                    method: 'GET',
                    responseType: 'json'
                })
                .then(res => {
                    self.destroyDataTable('#menuTable');
                    self.menus = res.data.menus;
                    self.initDataTable('#menuTable');
                })
                .catch(err => console.log(err));
            },
            showAddMenuForm: function(){
                this.errors.name = "";
                $('#addMenuModal').modal('show');
                this.resetForm();
            },
            addMenu: function(menu) {
                console.log( menu );
                let self = this;
                let url = this.prefix+'/menu';
                axios({
                    url: url,
                    method: 'POST',
                    data: menu,
                    responseType: 'json'
                })
                .then(res => {
                    if( res.data.success == true ) {
                        self.errors.name = "";
                        self.fetchMenus();
                        self.resetForm();
                        self.closeModal();
                        this.$toast.success('Created Successfully!')
                    }else {
                        self.errors.name = res.data.errors.name[0];
                    }

                })
                .catch(err => console.log(err));

            },
            showEditMenuForm: function(id){
                this.errors.name = "";
                let self = this;
                let url = this.prefix+'/menu/'+id;
                axios({
                    url: url,
                    method: 'GET',
                    responseType: 'json'
                })
                .then(res => {
                    if(res.data.success == true) {
                        self.menu = res.data.menu;
                    }
                })
                .catch(err => console.log(err));
                $('#editMenuModal').modal('show');
            },
            updateMenu: function(menu){
                let self = this;
                let url = this.prefix+'/menu';
                axios({
                    url: url,
                    method: 'PUT',
                    data: menu,
                    responseType: 'json'
                })
                .then(res => {
                    if(res.data.success == true) {
                        self.errors.name = "";
                        self.fetchMenus();
                        self.resetForm();
                        self.closeModal();
                        this.$toast.success('Updated Successfully!')
                    }else if(res.data.success == false) {
                        self.errors.name = res.data.errors.name[0];
                    }
                })
                .catch(err => console.log(err));
            },
            deleteMenu: function(id){
                let self = this;
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this menu item',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it'
                }).then((result) => {
                    if (result.value) {
                        let url = this.prefix+'/menu/'+id;
                        axios({
                            url: url,
                            method: 'DELETE',
                            responseType: 'json'
                        })
                        .then(res => {
                            self.fetchMenus();
                            this.$toast.success('Deleted Successfully!')

                        })
                        .catch(err => console.log(err));

                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire(
                            'Cancelled',
                            'Your Menu is safe',
                            'error'
                        )
                    }
                });
            },
            cloneMenu(id) {
                let url = this.prefix+'/menu/clone/'+id;
                axios({
                    url: url,
                    method: 'POST',
                    responseType: 'json'
                })
                .then(res => {
                    if (res.data.success == true) {
                        this.fetchMenus();
                        this.$toast.success('Menu cloned Successfully!');
                    }

                })
                .catch(err => console.log(err));
            },
            resetForm: function(){
                this.menu = {};
            },
            closeModal: function(){
                $('.modal').modal('hide');
                //$('.modal-backdrop').remove();
            },
            initDataTable(selector, options={}){
                setTimeout(function(){
                    $(selector).DataTable().draw();
                },300);
            },
            destroyDataTable(selector){
                $(selector).DataTable().destroy();
            },
        },
        mounted() {
            this.fetchMenus();
        },
    }
</script>
