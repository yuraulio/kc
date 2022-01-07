<template>
    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                            <i class="fe-eye font-22 avatar-title text-warning"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup">100</span>k</h3>
                            <p class="text-muted mb-1 text-truncate">Test</p>
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
            description: String,
            icon: String,
            color_class: String,
        },
        data() {
            return {
                value: "-",
            }
        },
        methods: {
            getData(){
                axios.get('/api/get_widget_data/' + this.type)
                    .then((response) => {
                        this.value = response.data["data"];
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
