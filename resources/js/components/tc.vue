<template>
    <div v-if="template">
        <div v-for="(val, index, key) in data" :key="key" class="col-12 mb-1">
            <div v-for="(column, indr, key) in val.columns" :key="key">
                <div v-if="column.template && column.active" class="card bg-grey">
                    <div class="card-body">
                        <div v-if="val.columns.length > 1">
                            <ul class="nav nav-pills navtab-bg nav-justified">
                                <li v-for="(v, ind) in val.columns" :key="ind" class="nav-item">
                                    <a href="#home1" @click="setTabActive(index, ind)" data-bs-toggle="tab" aria-expanded="false" :class="'nav-link' + (v.active === true ? ' active' : '')">
                                        {{ v.template.title }} #{{ ind + 1 }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <h5 v-else class="card-title">{{ column.template.title }}</h5>
                    </div>
                    <div v-show="column.active" class="card-body">
                        <multiput
                            v-for="input in column.template.inputs"
                            :key="input.key"
                            :keyput="input.key"
                            :label="input.label"
                            :type="input.type"
                        />
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

import templateComponents from './template-components.json'
import multiput from './inputs/multiput.vue'

export default {
    props: ['template'],
    data() {
        return {
            lodash: _,
            data: []
        }
    },
    components: {
        multiput
    },
    computed: {
        extractedComponents() {
            return templateComponents;
        }
    },
    methods: {
        setTabActive(index, ind) {
            this.data[index].columns.forEach(column => { column.active = false });
            this.data[index].columns[ind].active = true;

        }
    },
    mounted() {
        var parsed = JSON.parse(this.template.rows);
        parsed.forEach(element => {
            element.columns.forEach(column => {
                column.active = column.order < 1 ? true : false;

                if (this.extractedComponents[column.component] != null) {
                    column.template = this.extractedComponents[column.component];
                }

            });
        });

        this.data = parsed;
        console.log(JSON.parse(this.template ? this.template.rows : ''))
    }
}
</script>

<style>
.bg-grey {
    background-color: rgb(234 237 239 / 48%) !important;
}
</style>
