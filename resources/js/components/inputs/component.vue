<style scoped>
.card > i {
    font-size: 50px;
}
.card {
    border-radius: 5px;
}
.modal-background {
    background-color: #f5f6f8;
}
</style>

<template>
    <div>
        <div v-if="value" class="row p-3">
            <div @click="$modal.show('component-' + id)" class="col-12 text-center">
                <div class="card mb-0">
                    <i :class="icon_class + ' text-center p-2 pt-3'"></i>
                    <div class="card-body">
                        <h5 class="card-title text-center mb-0">{{ title }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <button
            v-else
            @click="$modal.show('component-' + id)"
            type="button"
            class="btn btn-secondary waves-effect waves-light m-1 text-center"
        >
            <i class="fas fa-plus"></i>
        </button>

        <modal class="modal-color" :name="'component-' + id" :resizable="true" height="auto" :adaptive="true">
            <div class="modal-background">
                <h4 class="pt-3 mt-0">Select Component</h4>
                <div class="row p-3">
                    <div @click="selectComponent('hero', 'fas fa-expand', 'Hero')" class="col-3">
                        <div class="card mb-0">
                            <i class="fas fa-expand text-center p-2"></i>
                            <div class="card-body">
                                <h5 class="card-title text-center mb-0">Hero</h5>
                            </div>
                        </div>
                    </div>

                    <div @click="selectComponent('text_box', 'fas fa-pen', 'Text')" class="col-3">
                        <div class="card mb-0">
                            <i class="fas fa-pen text-center p-2"></i>
                            <div class="card-body">
                                <h5 class="card-title text-center mb-0">Text</h5>
                            </div>
                        </div>
                    </div>

                    <div @click="selectComponent('content_box', 'fas fa-stop', 'Feature')" class="col-3">
                        <div class="card mb-0">
                            <i class="fas fa-stop text-center p-2"></i>
                            <div class="card-body">
                                <h5 class="card-title text-center mb-0">Feature</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
import { uuid } from 'vue-uuid';

export default {
    props: {
        propValue: null,
        propIcon: null,
        propTitle: null,
        required: false,
        order: Number,
    },
    data() {
        return {
            value: null,
            id: this.$uuid.v4(),
            icon_class: null,
            title: null,
        };
    },
    watch: {
        propValue: function () {
            this.value = this.propValue;
            this.icon_class = this.propIcon;
            this.title = this.propTitle;
        },
        value: function () {
            this.$emit('updatecomponent', [this.value, this.order, this.icon_class, this.title]);
        },
    },
    methods: {
        selectComponent(value, icon_class, title) {
            this.value = value;
            this.icon_class = icon_class;
            this.title = title;
            this.$modal.hide('component-' + this.id);
        },
    },
    mounted() {
        if (this.propValue) {
            this.value = this.propValue;
        }
        if (this.propIcon) {
            this.icon_class = this.propIcon;
        }
        if (this.propTitle) {
            this.title = this.propTitle;
        }
    },
};
</script>
