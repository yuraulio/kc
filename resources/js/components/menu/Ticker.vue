<template>
    <div class="nest-menu menu-datatable">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="create-btn col-md-12" style="margin-top: -38px;">
                       <button
                            v-on:click="showAddMenuForm"
                            class="btn btn-soft-info mat-raised-button float-end add-menu-button"
                            data-target="#addMenuModal">
                                <i class="mdi mdi-plus-circle me-1"></i>
                                Add Menu
                        </button>
                    </div>
                     
                    <div class="col-md-12 col-sm-12 dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="table-responsive">
                            <table class="table" id="menuTable" >
                                <thead>
                                    <tr>
                                        <th width="120">Id</th>
                                        <th> Name </th>
                                        <th> Title </th>
                                        <th> Mobile visibility </th>
                                        <th class="action-head text-end" >Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="menu in menus" :key="menu.id">
                                        <td class="align-middle">{{ menu.id }}</td>
                                        <td class="align-middle">{{ menu.name }}</td>
                                        <td class="align-middle">{{ menu.custom_class }}</td>
                                        <td class="align-middle">
                                            <template v-if="menu.url == '1'">
                                                <i class="fa fa-check-circle" aria-hidden="true"></i>
                                            </template>
                                        </td>
                                        <td class="action-buttons text-right align-middle">

                                            <a v-on:click="deleteMenu(menu.id)" :data-id="menu.id" href="javascript:void(0);" class="action-icon float-end" title="Delete menu">
                                                <i class="mdi mdi-delete"></i>
                                            </a>

                                            <a v-on:click="cloneMenu(menu.id)" :data-id="menu.id" href="javascript:void(0);" class="action-icon float-end" title="Clone menu">
                                                <i class="fa fa-clone data-table-actions" aria-hidden="true"></i>
                                            </a>

                                            <a v-on:click="showEditMenuForm(menu.id)" :data-id="menu.id" href="javascript:void(0);" class="action-icon float-end" title="Edit menu">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>

                                            <a :href="prefix+'/menus/builder/'+menu.id" class="action-icon float-end" title="Build menu">
                                                <i class="fa fa-bars data-table-actions" aria-hidden="true"></i>
                                            </a>

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
                        this.$toast.success('Updated Successfully!');
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
                    text: "You won't be able to revert this! Delete item?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    showLoaderOnConfirm: true,
                    buttonsStyling: false,
                    customClass: {
                        cancelButton: "btn btn-soft-secondary",
                        confirmButton: "btn btn-soft-danger",
                    },
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
                this.menu = {
                    url: '1'
                };
            },
            closeModal: function(){
                $('.modal').modal('hide');
                //$('.modal-backdrop').remove();
            },
            initDataTable(selector, options={}){
                setTimeout(function(){
                    $(selector).DataTable(
                        {
                        "dom": "<'row'<'col-sm-12 col-md-6'f><'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'test>>" +
                                "<'row'<'col-sm-12'tr>>" +
                                "<'row'<'col-sm-12'p>>",
                        }
                    ).draw();

                    $(".dataTables_filter input").attr('placeholder', 'Search...').removeClass("form-control-sm");
                    $(".dataTables_length select").removeClass("form-control-sm").addClass("btn btn-soft-secondary");

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

<style>
.menu-datatable .dataTables_filter>label{
    font-size: 0px;
    float: left;
}
.menu-datatable .dataTables_length>label{
    font-size: 0px;
    float: right;
    margin-right: 120px;
}
.menu-datatable .dataTables_length select {
    border-color: #d6d8db;
}
.menu-datatable .dataTables_length select:focus {
    color: #6c757d;
    background-color: rgba(108, 117, 125, 0.18);
    border-color: #d6d8db;
}
.menu-datatable .dataTables_length select:hover {
    color: #fff;
    background-color: #6c757d;
}
.menu-datatable .dataTables_length select option {
    background-color: #fff;
    color: #6c757d;
}
.add-menu-button {
    transform: translateY(38px);
}
.menu-datatable tbody tr:nth-of-type(odd) {
    background-color: #f3f7f9;
}
.menu-datatable .data-table-actions {
    font-size: 16px;
    transform: translateY(-1px);
}
.menu-datatable thead th::before {
    content: ""!important;
}
.menu-datatable thead th::after {
    content: ""!important;
}
.menu-datatable .table-responsive {
    margin-bottom: 0px;
}
.menu-datatable .pagination {
    margin-top: 13px!important;
}

</style>
