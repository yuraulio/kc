<template>
    <div>
        <div v-for="component in extractedComponents" :key="component.key" class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ component.title }}</h5>
                </div>
                <div class="card-body">
                    <multiput
                        v-for="input in component.inputs"
                        :key="input.key"
                        :keyput="input.key"
                        :label="input.label"
                        :type="input.type"
                    />
                </div>
            </div>
        </div>
        Template Components

    </div>
</template>

<script>

import templateComponents from './template-components.json'
import multiput from './inputs/multiput.vue'

export default {
    components: {
        multiput
    },
    computed: {
        extractedComponents() {
            return templateComponents;
        }
    },
}
</script>
