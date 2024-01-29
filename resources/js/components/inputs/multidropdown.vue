<template>
    <div :class="marginbottom">
        <label v-if="title" for="projectname" class="form-label">{{ title }} <span v-if="required">*</span> </label>

        <!-- <select @change="$emit('updatevalue', value)" v-model="value" class="form-select my-1 my-md-0">
        <option v-for="item in list" :value="item.id">{{item.title}}</option>
    </select> -->

        <multiselect
            deselectLabel=""
            selectLabel=""
            @input="inputed"
            v-model="value"
            :placeholder="placeholder"
            :multiple="multi"
            :taggable="taggable"
            :label="label"
            track-by="id"
            :options="list"
            @tag="addedTag"
            :allowEmpty="allowEmpty"
            tagPlaceholder="Press enter to add a category"
        >
            <template v-if="route == 'subcategories'" slot="tag" slot-scope="props">
                <div v-if="props.option.edit == true" class="d-inline-block">
                    <span class="multiselect__tag">
                        <input class="edit-input" v-model="props.option.title" />
                        <i
                            @click="props.option.edit = false"
                            aria-hidden="true"
                            tabindex="1"
                            class="multiselect__tag-icon dripicons-checkmark confirm-icon"
                        ></i>
                    </span>
                </div>
                <div v-else class="d-inline-block">
                    <span class="multiselect__tag multiselect__tag-padding">
                        <span>{{ props.option.title }}</span>
                        <i
                            @click="setEdit(props.option)"
                            aria-hidden="true"
                            tabindex="1"
                            class="multiselect__tag-icon dripicons-document-edit edit-icon"
                        ></i>
                        <i
                            @click="removeTag(props.option.title)"
                            aria-hidden="true"
                            tabindex="1"
                            class="multiselect__tag-icon"
                        ></i>
                    </span>
                </div>
            </template>
        </multiselect>
    </div>
</template>

<script>
export default {
    props: {
        title: String,
        propValue: null,
        required: false,
        route: String,
        multi: {
            default: true,
        },
        fetch: {
            default: true,
        },
        taggable: {
            default: false,
        },
        data: null,
        placeholder: {
            default: 'Pick some',
        },
        marginbottom: {
            default: 'mb-3',
        },
        allowEmpty: {
            default: true,
        },
        setlabel: {
            default: null,
        },
    },
    data() {
        return {
            value: [],
            list: [],
            editTag: false,
            label: 'title',
        };
    },
    watch: {
        propValue: function () {
            this.value = this.propValue;
        },
        data: function () {
            this.list = this.data;
        },
    },
    methods: {
        addedTag(tag) {
            var arr = JSON.parse(JSON.stringify(this.value));
            arr.push({
                title: tag,
                id: tag,
                new: true,
                edit: false,
            });
            this.$emit('updatevalue', arr);
        },
        inputed($event) {
            this.$emit('updatevalue', $event);
        },
        get() {
            axios
                .get('/api/' + this.route)
                .then((response) => {
                    if (response.status == 200) {
                        if (this.route == 'getMenus') {
                            // console.log(response.data.menus);
                            this.label = 'name';
                            this.list = response.data.menus;

                            this.setMenuIds();
                            this.updateSelectedMenu();

                            this.list.push({
                                id: null,
                                name: 'NONE',
                            });
                        } else {
                            var data = response.data.data;
                            this.list = data;
                        }
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        removeTag(title) {
            var index = this.value.findIndex(function (tag) {
                return tag.title == title;
            });
            this.value.splice(index, 1);
        },
        setMenuIds() {
            var value = this.value;
            if (!('id' in this.value)) {
                if (menu !== undefined) {
                    var index = this.list.findIndex(function (menu) {
                        return menu.name == value.name;
                    });
                    if (index != -1) {
                        this.value.id = this.list[index].id;
                    }
                }
            }
        },
        updateSelectedMenu() {
            var value = this.value;
            var index = this.list.findIndex(function (menu) {
                return menu.id == value.id;
            });

            this.value = this.list[index];
        },
        setEdit(tag) {
            this.$set(tag, 'edit', true);
        },
    },
    mounted() {
        if (this.propValue) {
            this.value = this.propValue;
        }

        if (this.data) {
            this.list = this.data;
        }

        if (this.fetch) {
            this.get();
        }

        if (this.setlabel) {
            this.label = this.setlabel;
        }
    },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style>
.multiselect__tag {
    background: #6658dd !important;
}
.multiselect__option--highlight {
    background: #6658dd !important;
}
.multiselect__tags {
    font-size: 14px;
    font-size: 0.875rem;
    font-weight: 400;
    line-height: 1.5;
    color: #6c757d;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: 0.2rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.multiselect__tag-icon:hover {
    background: #4535cb !important;
}
.multiselect__tag-icon::after {
    color: white !important;
}

.edit-icon::after {
    content: '';
}
.edit-icon::before {
    margin-top: 2px;
}
.edit-icon {
    right: 20px;
    top: 1px;
}
.confirm-icon::after {
    content: '';
}
.confirm-icon::before {
    margin-top: 10px;
}
.confirm-icon {
    right: 0px;
    top: 1px;
}

.multiselect__tag-padding {
    padding-right: 50px;
}
.edit-input {
    height: 30px;
}
</style>
