<template>
    <div class="menu-modals">
        <!-- Modal -->
        <div class="modal fade" id="addMenuModal" tabindex="-1" role="dialog" aria-labelledby="addMenuModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addMenuModalLabel">Add Menu</h5>

                    </div>
                    <form method="post" action="" v-on:submit.prevent="addMenu(menu)">
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="add_menu_title" class="form-label">Name <span style="color: red">*</span></label>
                                <input type="text" name="name" id="add_menu_title" v-bind:class="{error:errors.name}" class="form-control input-field mb-2" v-model="menu.name">

                            </div>
                            <div class="form-group mb-3">
                                <label for="add_menu_url" class="form-label">URL</label>
                                <input type="text" name="url" id="add_menu_url" class="form-control input-field mb-2" v-model="menu.url">

                            </div>
                            <div class="form-group mb-3">
                                <label for="add_menu_custom_class" class="form-label">Title</label>
                                <input type="text" id="add_menu_custom_class" name="custom_class" class="form-control input-field" v-model="menu.custom_class">

                            </div>

                            <div class="m-footer pull-right mt-10">
                                <button @click="closeModal" type="button" class="btn btn-danger cs-danger" data-dismiss="modal">Close</button>
                                <button type="submit" id="add_menu_item_btn" class="btn btn-info edit-info">Add</button>
                            </div>

                        </div>

                    </form>
                </div>
            </div>
        </div>
        <!-- Edit Modal -->
        <div class="modal fade" id="editMenuModal" tabindex="-1" role="dialog" aria-labelledby="editMenuModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMenuItemModalLabel">Edit Menu</h5>

                    </div>
                    <form method="post" action="" v-on:submit.prevent="updateMenu(menu)">
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="edit_menu_item_title" class="form-label">Name <span style="color: red">*</span></label>
                                <input type="text" name="name" class="form-control input-field mb-2" v-bind:class="{error:errors.name}" v-model="menu.name" />

                            </div>

                            <div class="form-group mb-3">
                                <label for="url" class="form-label">URL</label>
                                <input type="text" name="url" class="form-control input-field mb-2" v-model="menu.url">

                            </div>
                            <div class="form-group mb-3">
                                <label for="url" class="form-label">Title</label>
                                <input type="text" name="custom_class" class="form-control input-field " v-model="menu.custom_class" placeholder="Custom Class">

                            </div>
                            <div class="m-footer pull-right">
                                <button @click="closeModal" type="button" class="btn btn-danger cs-danger" data-dismiss="modal">Close</button>
                                <button type="submit" id="edit_menu_item_btn" class="btn btn-info edit-info">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            menu: Object,
            errors: Object,
            updateMenu: Function,
            addMenu: Function,
        },
        name: 'menu-modals',
        methods: {
            closeModal() {
                $('.modal').modal('hide');
            }
        }
    }
</script>
