<template>

<div class="mb-3">
    <label class="form-label">{{title}} <span v-if="required">*</span> </label>

    <row 
        v-for="row in rows" 
        v-bind:key="row.order"
        :order="row.order"
        @updatevalue="updatevalue"
    ></row>

    <div class="row">
        <div class="col-12 text-center">
            <button @click="addRow()" type="button" class="btn btn-secondary waves-effect waves-light m-1"><i class="fas fa-plus"></i></button>
        </div>
    </div>
</div>

</template>

<script>
    export default {
        props: {
            title: String,
            propValue: null,
            required: false
        },
        data() {
            return {
                rows: [],
            }
        },
        watch: {
            "propValue": function() {
                this.rows = this.propValue;
            }
        },
        methods: {
            addRow(){
                var order = 0;
                this.rows.forEach(row => {
                    if (row.order > order) {
                        order = row.order;
                    }
                });
                order = order + 1;
                this.rows.push(
                    {
                        order: order,
                        description: "",
                        columns: [{
                            order: 0,
                            component: null,
                        }],
                    }
                );
            },
            updatevalue(value){
                // console.log(value);
                var test = this.rows.findIndex(function(row) {
                    return row.order == value.order;
                });
                console.log(value);
            }
        },
        mounted() {
        }
    }
</script>