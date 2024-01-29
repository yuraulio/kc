<template>
    <div class="card mb-2">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="create-btn text-end">
                                <a href="/menus" class="btn btn-soft-secondary edit-info me-2 float-start">
                                    <i class="fe-x me-1"></i>
                                    Cancel
                                </a>
                                <button
                                    v-on:click="showSettingsForm"
                                    class="btn btn-soft-secondary edit-info me-2"
                                    data-target="#settingsModal"
                                >
                                    <i class="mdi mdi-cog me-1"></i>
                                    Settings
                                </button>
                                <button
                                    v-on:click="showAddMenuItemForm"
                                    class="btn btn-soft-info"
                                    data-target="#addMenuItemModal"
                                >
                                    <i class="mdi mdi-plus-circle me-1"></i>
                                    Add Menu Item
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="dd" id="nestmenu">
                        <draggable-menu
                            v-if="renderComponent"
                            :prefix="prefix"
                            :lists="lists"
                            :settings="settings"
                            :defaultSettings="defaultSettings"
                            :isDestroyAble="isDestroyAble"
                            :editMenuItem="editMenuItem"
                            :deleteMenuItem="deleteMenuItem"
                        >
                        </draggable-menu>
                    </div>
                    <menu-item-modals
                        :items="items"
                        :item="item"
                        :menu="menu"
                        :parents="parents"
                        :settings="settings"
                        :defaultSettings="defaultSettings"
                        :errors="errors"
                        :update-menu-item="updateMenuItem"
                        :add-menu-item="addMenuItem"
                        :add-menu-setting="addMenuSetting"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import draggableMenu from './DraggableMenu';
import menuItemModals from './MenuItemModals';
export default {
    name: 'menu-builder',
    components: {
        'draggable-menu': draggableMenu,
        'menu-item-modals': menuItemModals,
    },
    props: ['menu', 'prefix'],
    data() {
        return {
            lists: [],
            items: [],
            item: {
                menu_id: this.menu.id,
                id: '',
                title: '',
                url: '',
                route: '',
                params: '',
                paramItems: [],
                controller: '',
                middleware: '',
                target: '_self',
                parent_id: '',
                custom_class: '',
                apply_child_as_parent: 0,
            },
            childrens: [],
            parents: [],
            settings: {},
            defaultSettings: {},
            successMsg: '',
            renderComponent: true,
            errors: {
                title: '',
            },
            isDestroyAble: false,
        };
    },
    created() {
        this.fetchMenuItems();
    },
    methods: {
        fetchMenuItems: function () {
            this.renderComponent = false;
            let self = this;
            let url = this.prefix + '/menu/items/' + this.menu.id;

            axios
                .get(url, {
                    responseType: 'json',
                })
                .then((res) => {
                    console.log(res);
                    if (res.data.success == true) {
                        self.lists = res.data.lists;
                        self.items = res.data.items;
                        self.settings = res.data.settings;
                        self.settings.menu_id = self.menu.id;
                        self.settings.apply_child_as_parent = parseInt(self.settings.apply_child_as_parent);
                        self.settings.levels = JSON.stringify(self.settings.levels, null, 4);
                        self.defaultSettings = res.data.default;
                        self.renderComponent = true;
                    }
                })
                .catch((err) => console.log(err));
        },
        showSettingsForm: function () {
            $('#settingsModal').modal('show');
        },
        showDesignModal() {
            $('#showMenuModel').modal('show');
        },
        showAddMenuItemForm: function () {
            this.errors.title = '';
            this.resetForm();
            $('#addMenuItemModal').modal('show');
        },
        addMenuItem: function (item) {
            event.preventDefault();
            let url = this.prefix + '/menu/item';
            let self = this;

            this.prepareParams();

            axios({
                method: 'post',
                url: url,
                data: this.item,
                responseType: 'json',
            })
                .then((res) => {
                    if (res.data.success == true) {
                        this.$toast.success('Created Successfully!');
                        self.errors.title = '';
                        self.resetForm();
                        self.fetchMenuItems();
                        self.isDestroyAble = true;
                        self.closeModal();
                    } else {
                        self.errors.title = res.data.errors.title[0];
                    }
                })
                .catch((err) => console.log(err));
        },
        editMenuItem(id) {
            this.errors.title = '';
            var self = this;
            let url = this.prefix + '/menu/' + this.menu.id + '/item/' + id;

            axios({
                method: 'get',
                url: url,
                responseType: 'json',
            })
                .then((res) => {
                    if (res.data.success == true) {
                        self.item = res.data.item;
                        self.item.parent_id = res.data.item.parent_id ? res.data.item.parent_id : '';
                        self.item.apply_child_as_parent = parseInt(self.settings.apply_child_as_parent);
                        self.childrens = res.data.childrens;
                        self.parents = res.data.parents;

                        this.setUpParams();
                    }
                })
                .catch((err) => console.log(err));
            $('#editMenuItemModal').modal('show');
        },
        updateMenuItem: function (item) {
            event.preventDefault();
            var self = this;
            var url = this.prefix + '/menu/item';

            this.prepareParams();

            axios({
                method: 'put',
                url: url,
                data: this.item,
            })
                .then((res) => {
                    if (res.data.success == true) {
                        self.errors.title = '';
                        self.fetchMenuItems();
                        self.isDestroyAble = true;
                        self.closeModal();
                        this.$toast.success('Updated Successfully!');
                    } else {
                        self.errors.title = res.data.errors.title[0];
                    }
                })
                .catch((err) => console.log(err));
            $('.modal').modal('hide');
        },
        deleteMenuItem: function (id) {
            event.preventDefault();
            let self = this;

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this! Delete item?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                showLoaderOnConfirm: true,
                buttonsStyling: false,
                customClass: {
                    cancelButton: 'btn btn-soft-secondary',
                    confirmButton: 'btn btn-soft-danger',
                },
            }).then((result) => {
                if (result.value) {
                    let url = this.prefix + '/menu/item/' + id;

                    axios({
                        method: 'delete',
                        url: url,
                    })
                        .then((res) => {
                            if (res.data.success == true) {
                                self.fetchMenuItems();
                                self.isDestroyAble = true;
                                this.$toast.success('Deleted Successfully!');
                            }
                        })
                        .catch((err) => console.log(err));
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire('Cancelled', 'Your imaginary file is safe :)', 'error');
                }
            });
        },
        addMenuSetting: function (settings) {
            settings.levels = JSON.parse(settings.levels);
            let url = this.prefix + '/menu/item/settings';
            let self = this;

            axios({
                method: 'post',
                url: url,
                data: settings,
                responseType: 'json',
            })
                .then((res) => {
                    if (res.data.success == true) {
                        self.resetForm();
                        self.fetchMenuItems();
                        self.isDestroyAble = true;
                        this.$toast.success('Updated Successfully!');
                        self.closeModal();
                    }
                })
                .catch((err) => console.log(err));
        },
        prepareParams: function () {
            let params = {};
            for (let paramItem of this.item.paramItems) {
                if (paramItem.key.length > 0) {
                    params[paramItem.key] = paramItem.value;
                }
            }
            this.item.params = JSON.stringify(params);
        },
        setUpParams: function () {
            this.item.paramItems = [];
            let params = JSON.parse(this.item.params);
            for (let param in params) {
                console.log(param);
                this.item.paramItems.push({ key: param, value: params[param] });
            }
        },
        resetForm: function () {
            this.item.menu_id = this.menu.id;
            this.item.id = '';
            this.item.title = '';
            this.item.url = '';
            this.item.route = '';
            this.item.params = '';
            this.item.paramItems = [];
            this.item.controller = '';
            this.item.middleware = '1';
            this.item.target = '_self';
            this.item.parent_id = '';
            this.item.icon = '';
            this.item.custom_class = '';
        },
        closeModal: function () {
            $('.modal').modal('hide');
            //$('.modal-backdrop').remove();
        },
    },
};
</script>
<style scoped></style>
