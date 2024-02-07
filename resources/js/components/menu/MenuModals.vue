<template>
  <div class="menu-modals">
    <!-- Modal -->
    <div
      class="modal fade"
      id="addMenuModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addMenuModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addMenuModalLabel">Add Menu</h5>
          </div>
          <form method="post" action="" v-on:submit.prevent="addMenu(menu)">
            <div class="modal-body">
              <div class="form-group mb-3">
                <label for="add_menu_title" class="form-label">Name <span style="color: red">*</span></label>
                <input
                  type="text"
                  name="name"
                  id="add_menu_title"
                  v-bind:class="{ error: errors.name }"
                  class="form-control input-field mb-2"
                  v-model="menu.name"
                />
              </div>
              <!-- <div class="form-group mb-3">
                                <label for="add_menu_url" class="form-label">URL</label>
                                <input type="text" name="url" id="add_menu_url" class="form-control input-field mb-2" v-model="menu.url">

                            </div> -->
              <div class="form-group mb-3">
                <label for="add_menu_custom_class" class="form-label">Title</label>
                <input
                  type="text"
                  id="add_menu_custom_class"
                  name="custom_class"
                  class="form-control input-field"
                  v-model="menu.custom_class"
                />
              </div>

              <div class="form-check form-switch mb-3">
                <input
                  class="form-check-input"
                  type="checkbox"
                  id="flexSwitchCheckChecked"
                  @click="toggleMobile()"
                  :checked="menu.url == '1'"
                />
                <label class="form-check-label" for="flexSwitchCheckChecked">Mobile visibility</label>
              </div>

              <div class="m-footer mt-10 text-center">
                <button type="submit" id="add_menu_item_btn" class="btn btn-soft-success edit-info m-1">
                  <i class="mdi mdi-square-edit-outline me-1"></i>
                  Create
                </button>
                <button
                  @click="closeModal"
                  type="button"
                  class="btn btn-soft-secondary cs-danger m-1"
                  data-dismiss="modal"
                >
                  <i class="fe-x me-1"></i>
                  Cancel
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Edit Modal -->
    <div
      class="modal fade"
      id="editMenuModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="editMenuModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editMenuItemModalLabel">Edit Menu</h5>
          </div>
          <form method="post" action="" v-on:submit.prevent="updateMenu(menu)">
            <div class="modal-body">
              <div class="form-group mb-3">
                <label for="edit_menu_item_title" class="form-label">Name <span style="color: red">*</span></label>
                <input
                  type="text"
                  name="name"
                  class="form-control input-field mb-2"
                  v-bind:class="{ error: errors.name }"
                  v-model="menu.name"
                />
              </div>

              <!-- <div class="form-group mb-3">
                                <label for="url" class="form-label">URL</label>
                                <input type="text" name="url" class="form-control input-field mb-2" v-model="menu.url">

                            </div> -->
              <div class="form-group mb-3">
                <label for="url" class="form-label">Title</label>
                <input
                  type="text"
                  name="custom_class"
                  class="form-control input-field"
                  v-model="menu.custom_class"
                  placeholder="Custom Class"
                />
              </div>

              <div class="form-check form-switch mb-3">
                <input
                  class="form-check-input"
                  type="checkbox"
                  id="flexSwitchCheckChecked"
                  @click="toggleMobile()"
                  :checked="menu.url == '1'"
                />
                <label class="form-check-label" for="flexSwitchCheckChecked">Mobile visibility</label>
              </div>

              <div class="m-footer text-center">
                <button type="submit" id="edit_menu_item_btn" class="btn btn-soft-success edit-info m-1">
                  <i class="mdi mdi-square-edit-outline me-1"></i>
                  Save
                </button>
                <button
                  @click="closeModal"
                  type="button"
                  class="btn btn-soft-secondary cs-danger m-1"
                  data-dismiss="modal"
                >
                  <i class="fe-x me-1"></i>
                  Cancel
                </button>
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
    },
    toggleMobile() {
      if (this.menu.url == '1') {
        this.menu.url = '0';
      } else {
        this.menu.url = '1';
      }
    },
  },
};
</script>

<style scoped>
.modal-header {
  background-color: #edeff1;
  padding: 23px;
}
</style>
