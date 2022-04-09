<style scoped>
    .widget-min-height {
        min-height: 168px;
    }
</style>

<template>
    <div class="col-md-6 col-xl-3">
        <div class="card widget-min-height" id="tooltip-container">
            <div class="card-body">
                <i class="fe-info text-muted float-end" data-bs-container="#tooltip-container" data-bs-toggle="tooltip" 
                data-bs-placement="bottom" :title="title"></i>
                <h4 class="mt-0 font-16">{{ title }}</h4>
                <h2 v-if="value" class="text-primary my-3 text-center"><span>{{value}}</span></h2>
                <vue-loaders-ball-beat v-else color="#6658dd" scale="1" class="mt-4 text-center d-block"></vue-loaders-ball-beat>
                <p v-if="students_in_class" class="text-muted mb-0">In class: {{students_in_class}} 
                    <span class="float-end">Online: {{students_in_online}}</span>
                </p>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: {
            title: String,
            type: String,
        },
        data() {
            return {
                value: 0,
                students_in_class: 0,
                students_in_online: 0,
            }
        },
        methods: {
            getData(){
                // const axios = require('axios').default;
                axios.get('/api/get_widget_data/' + this.type)
                    .then((response) => {
                        if (this.type == "students") {
                            this.students_in_class = response.data["data"][0]; 
                            this.students_in_online = response.data["data"][1]; 
                            this.value = this.students_in_class + this.students_in_online;
                        } else {
                            this.value = response.data["data"];
                        }
                    })
                    .catch((error) => {
                        console.log(error)
                    });
            },
        },
        mounted() {
            this.getData();
        }
    }
</script>
