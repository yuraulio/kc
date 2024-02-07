<template>
  <div class="menu-modals">
    <!-- Modal -->
    <div
      class="modal fade"
      id="addMenuItemModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="addMenuItemModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addMenuItemModalLabel">Add Menu Item</h5>
          </div>
          <form method="post" action="" v-on:submit.prevent="addMenuItem(item)">
            <div class="modal-body">
              <div class="form-group mb-3">
                <label for="add_menu_item_title" class="form-label">Title <span style="color: red">*</span></label>
                <input
                  type="text"
                  name="title"
                  class="form-control input-field mb-2"
                  v-bind:class="{ error: errors.title }"
                  id="add_menu_item_title"
                  v-model="item.title"
                />
              </div>
              <div class="form-group mb-3">
                <label for="add_menu_item_url" class="form-label">URL</label>
                <input
                  type="text"
                  name="url"
                  class="form-control input-field"
                  id="add_menu_item_url"
                  v-model="item.url"
                />
              </div>
              <div class="form-group mb-3">
                <label for="add_menu_item_target" class="form-label">Open In</label>
                <select
                  name="target"
                  id="add_menu_item_target"
                  v-model="item.target"
                  class="form-control input-field mb-2"
                >
                  <option value="_self">Same Tab</option>
                  <option value="_blank">New Tab</option>
                </select>
              </div>
              <div class="form-group mb-3">
                <label for="add_parent_id" class="form-label">Parent</label>
                <select
                  name="parent_id"
                  id="add_parent_id"
                  v-model="item.parent_id"
                  class="form-control input-field parent"
                >
                  <option value="">Select parent</option>
                  <option v-for="parent in items" :key="parent.id" :value="parent.id">
                    {{ parent.title }}
                  </option>
                </select>
              </div>
              <div class="form-group mb-3">
                <label for="add_menu_item_custom_class" class="form-label">Icon</label>
                <input
                  type="text"
                  name="icon"
                  class="form-control input-field mb-2"
                  id="add_menu_item_icon"
                  v-model="item.icon"
                />
              </div>
              <div class="form-group mb-3">
                <label for="add_menu_item_custom_class" class="form-label">Custom Class</label>
                <input
                  type="text"
                  name="custom_class"
                  class="form-control input-field mb-2"
                  id="add_menu_item_custom_class"
                  v-model="item.custom_class"
                />
              </div>
              <div class="form-check form-switch mb-3">
                <input
                  class="form-check-input"
                  type="checkbox"
                  id="flexSwitchCheckChecked"
                  @click="toggleMobile()"
                  :checked="item.middleware == '1'"
                />
                <label class="form-check-label" for="flexSwitchCheckChecked">Mobile visibility</label>
              </div>
              <div class="m-footer text-center">
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
      id="editMenuItemModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="editMenuItemModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editMenuItemModalLabel">Edit Menu Item</h5>
          </div>
          <form method="post" action="" v-on:submit.prevent="updateMenuItem(item)">
            <div class="modal-body">
              <div class="form-group mb-3">
                <label for="add_menu_item_title" class="form-label">Title <span style="color: red">*</span></label>
                <input
                  type="text"
                  name="title"
                  class="form-control input-field mb-2"
                  v-bind:class="{ error: errors.title }"
                  v-model="item.title"
                />
              </div>
              <div class="form-group mb-3">
                <label for="url" class="form-label">URL</label>
                <input type="text" name="url" class="form-control input-field" v-model="item.url" />
              </div>
              <div class="form-group mb-3">
                <label for="edit_menu_item_target" class="form-label">Open In</label>
                <select name="target" id="edit_menu_item_target" class="form-control input-field" v-model="item.target">
                  <option class="red" value="_self">Same Tab</option>
                  <option class="red" value="_blank">New Tab</option>
                </select>
              </div>
              <div class="form-group mb-3">
                <label for="edit_parent_id" class="form-label">Parent</label>
                <div v-if="item.applyChildAsParent">
                  <select name="parent_id" class="form-control input-field parent" v-model="item.parent_id">
                    <option value="" selected>Select parent</option>
                    <option v-if="parent.id != item.id" v-for="parent in items" :key="parent.id" :value="parent.id">
                      {{ parent.title }}
                    </option>
                  </select>
                </div>
                <div v-else>
                  <select name="parent_id" class="form-control input-field parent" v-model="item.parent_id">
                    <option value="" selected>Select parent</option>
                    <option v-if="parent.id != item.id" v-for="parent in parents" :key="parent.id" :value="parent.id">
                      {{ parent.title }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="form-group mb-3">
                <label for="add_menu_item_custom_class" class="form-label">Icon</label>
                <input
                  type="text"
                  name="icon"
                  class="form-control input-field mb-2"
                  id="add_menu_item_icon"
                  v-model="item.icon"
                />
              </div>
              <div class="form-group mb-3">
                <label for="add_menu_item_custom_class" class="form-label">Custom Class</label>
                <input
                  type="text"
                  name="custom_class"
                  class="form-control input-field mb-2"
                  v-model="item.custom_class"
                />
              </div>
              <div class="form-check form-switch mb-3">
                <input
                  class="form-check-input"
                  type="checkbox"
                  id="flexSwitchCheckChecked"
                  @click="toggleMobile()"
                  :checked="item.middleware == '1'"
                />
                <label class="form-check-label" for="flexSwitchCheckChecked">Mobile visibility</label>
              </div>
              <div class="m-footer text-center">
                <button type="submit" id="add_menu_item_btn" class="btn btn-soft-success edit-info m-1">
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
    <!-- Settings Modal -->
    <div
      class="modal fade"
      id="settingsModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="settingModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="settingModalLabel">Settings</h5>
          </div>
          <form method="post" action="" v-on:submit.prevent="addMenuSetting(settings)">
            <input type="hidden" name="menu_id" class="form-control input-field mb-2" v-model="settings.menu_id" />
            <div class="modal-body">
              <div class="form-group mb-3">
                <label for="depth" class="form-label">Depth</label>
                <input type="text" name="depth" class="form-control input-field mb-2" v-model="settings.depth" />
              </div>
              <div class="form-group mb-3">
                <label for="levels" class="form-label">Levels</label>
                <textarea
                  name="levels"
                  class="form-control"
                  style="resize: vertical; height: 100%; min-height: 120px"
                  v-model="settings.levels"
                ></textarea>
              </div>
              <div class="form-group mb-3">
                <label for="apply_child_as_parent" class="form-label"> Apply Child As Parent</label>
                <input
                  type="checkbox"
                  name="apply_child_as_parent"
                  id="apply_child_as_parent"
                  v-model="settings.apply_child_as_parent"
                />
              </div>
              <div class="m-footer text-center">
                <button type="submit" id="add_menu_item_btn" class="btn btn-soft-success edit-info m-1">
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

    <!-- Show Menu -->
    <div
      class="modal fade"
      id="showMenuModel"
      tabindex="-1"
      role="dialog"
      aria-labelledby="editMenuModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editMenuItemModalLabel">Display {{ menu.name }} Menu</h5>
          </div>
          <div class="modal-body">
            <div class="menu_items"></div>
            <div class="m-footer">
              <button
                @click="closeModal"
                type="button"
                class="btn btn-danger cs-danger float-right"
                data-dismiss="modal"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    items: Array,
    item: Object,
    menu: Object,
    settings: Object,
    defaultSettings: Object,
    parents: Array,
    errors: Object,
    menuHTML: String,
    updateMenuItem: Function,
    addMenuItem: Function,
    addMenuSetting: Function,
  },
  name: 'menu-item-modals',
  data() {
    return {
      showCollapse: false,
    };
  },
  methods: {
    closeModal() {
      $('.modal').modal('hide');
    },
    toggleMobile() {
      if (this.item.middleware == '1') {
        this.item.middleware = '0';
      } else {
        this.item.middleware = '1';
      }
    },
    addParam: function () {
      let paramItems = [...this.item.paramItems];
      this.$delete(this.item, 'paramItems');
      paramItems.push({ key: '', value: '' });
      this.item.paramItems = paramItems;
    },
    removeParam(index) {
      console.log(index);
      let paramItems = [...this.item.paramItems];
      paramItems.splice(index, 1);
      this.$delete(this.item, 'paramItems');
      this.item.paramItems = paramItems;
    },
  },
};
</script>

<style scoped="scoped">
.modal-dialog modal-dialog-centered {
  max-width: 700px;
}
.btn.btn-info {
  box-shadow: none;
}

.modal-header {
  background-color: #edeff1;
  padding: 23px;
}
</style>
