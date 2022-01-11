<style scoped>

</style>

<template>
    <row-box></row-box>
</template>

<script>
    export default {
        props: {
            
        },
        data() {
            return {
                value: 0,
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
            // this.getData();
        }
    }
</script>
