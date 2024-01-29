<style scoped>
.widget-min-height {
    min-height: 168px;
}
</style>

<template>
    <div class="col-md-6">
        <div class="card" id="tooltip-container">
            <div class="card-body">
                <i
                    class="fe-info text-muted float-end"
                    data-bs-container="#tooltip-container"
                    data-bs-toggle="tooltip"
                    data-bs-placement="bottom"
                    :title="description"
                ></i>
                <h4 class="mt-0 font-16">{{ title }}</h4>

                <vuetable v-if="value" ref="vuetable" :fields="fields" :api-mode="false" :data="value"> </vuetable>
                <vue-loaders-ball-beat
                    v-else
                    color="#6658dd"
                    scale="1"
                    class="mt-4 text-center d-block"
                ></vue-loaders-ball-beat>
            </div>
        </div>
    </div>
</template>

<script>
import Vuetable from 'vuetable-2';

export default {
    components: {
        Vuetable,
    },
    props: {
        title: String,
        type: String,
        description: String,
        fields: Array,
    },
    data() {
        return {
            value: null,
            students_in_class: 0,
            students_in_online: 0,
        };
    },
    methods: {
        getData() {
            // const axios = require('axios').default;
            axios
                .get('/api/get_widget_data/' + this.type)
                .then((response) => {
                    this.value = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
    mounted() {
        this.getData();
    },
};
</script>
