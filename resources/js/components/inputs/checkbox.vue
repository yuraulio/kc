<template>
    <div class="mb-3 form-check form-switch">
        <label for="" class="form-label">{{ title }} <span v-if="required">*</span> </label>
        <input @input="$emit('updatevalue', value)" type="text" class="form-check-input" v-model="value" />
    </div>
</template>

<script>
export default {
    props: {
        title: String,
        propValue: {},
        required: false,
    },
    data() {
        return {
            value: this.propValue,
        };
    },
    watch: {
        propValue: function () {
            this.value = this.propValue;
        },
    },
    methods: {},
    mounted() {},
};
</script>
