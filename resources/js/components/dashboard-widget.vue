<template>
    <div class="col-md-6 col-xl-4">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <div :class="'avatar-lg rounded-circle bg-soft-' + color + ' border-' + color + ' border'">
                            <i :class="icon + ' font-22 avatar-title text-' + color"></i>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="text-end">
                        <p class="text-muted mb-1 text-truncate">{{ title }}</p>
                            <h3 class="text-dark mt-1"><span>{{value}}</span></h3>
                            <p v-if="students_in_class" class="text-muted mb-1 text-truncate">IN-CLASS COURSES: {{ students_in_class }}</p>
                            <p v-if="students_in_online" class="text-muted mb-1 text-truncate">E-LEARNING COURSES: {{ students_in_online }}</p>
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
            title: String,
            type: String,
            icon: String,
            color: String,
        },
        data() {
            return {
                value: "-",
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
