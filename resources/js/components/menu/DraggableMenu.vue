<template>
    <ol class="dd-list">
        <li v-for="list in lists" :key="list.id" class="dd-item" :data-order="list.order" :data-id="list.id">
            <div class="dd-handle" style="padding-right: 70px">
                <span class="item=icon" v-html="list.icon"> {{ list.icon }}</span>
                <span class="item-title"> {{ list.title }}</span>
                <span class="item-url"> {{ list.url }}</span>
                <span v-if="list.middleware == '1'" class="ms-2 badge bg-primary"> Vissible on mobile </span>
                <span v-else class="ms-2 badge bg-secondary"> Hidden on mobile </span>
            </div>
            <div class="action-area">
                <a
                    href="#"
                    style="margin-top: 7px"
                    class="action-icon"
                    v-on:click="editMenuItem(list.id)"
                    :data-id="list.id"
                >
                    <i class="mdi mdi-square-edit-outline"></i>
                </a>
                <a
                    href="#"
                    style="margin-top: 7px"
                    class="action-icon"
                    v-on:click="deleteMenuItem(list.id)"
                    :data-id="list.id"
                >
                    <i class="mdi mdi-delete"></i>
                </a>
            </div>
            <draggable-menu
                v-if="list.childrens.length > 0"
                :prefix="prefix"
                :lists="list.childrens"
                :settings="settings"
                :defaultSettings="defaultSettings"
                :editMenuItem="editMenuItem"
                :deleteMenuItem="deleteMenuItem"
            >
            </draggable-menu>
        </li>
    </ol>
</template>

<script>
import './menustyle.css';
export default {
    props: {
        prefix: String,
        lists: Array,
        settings: Object,
        defaultSettings: Object,
        isDestroyAble: Boolean,
        editMenuItem: Function,
        deleteMenuItem: Function,
    },
    name: 'draggable-menu',
    data() {
        return {
            isNestMenu: false,
            depth: this.settings.depth ? this.settings.depth : this.defaultSettings.depth,
        };
    },
    created() {
        if (this.lists && this.lists.length > 0) {
            this.isNestMenu = true;
        }

        this.initNestable('#nestmenu');
    },
    methods: {
        initNestable: function (selector = '#nestable', options = {}) {
            let self = this;
            // activate Nestable for list 1
            setTimeout(function () {
                if (self.isNestMenu) {
                    if (self.isDestroyAble) {
                        $(selector).nestable('destroy');
                    }

                    $(selector).nestable({
                        group: 1,
                        maxDepth: parseInt(self.depth),
                        callback: function (l, e) {
                            // l is the main container
                            // e is the element that was moved

                            var list = l.length ? l : $(l.target);
                            var menus = list.nestable('toArray');

                            axios({
                                url: self.prefix + '/menu/item/sort',
                                method: 'POST',
                                responseType: 'json',
                                data: {
                                    menus: menus,
                                },
                            }).then((res) => {
                                if (res.data.success == true) {
                                    //this.$toast.success('Sorted Successfully!')
                                }
                            });
                        },
                    });
                }
            }, 1000);
        },
        destroyNestable(selector) {
            setTimeout(function () {
                $(selector).nestable('destroy');
            }, 500);
        },
    },
};
</script>
<style></style>
