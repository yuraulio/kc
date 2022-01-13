<template>

<div class="mb-3">
    <label class="form-label">{{title}} <span v-if="required">*</span> </label>

    <row v-for="row in rows" >

        <div v-for="column in row.columns" :class="'col-md-' + (12/row.columns.length)">
            gdfgfd
        </div>

    </row>
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
            },
            "rows": {
                handler: function() {
                    this.$emit('updatevalue', this.rows);
                },
                deep: true 
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
            updatevalue(new_row){
                var index = this.rows.findIndex(function(row) {
                    return row.order == new_row.order;
                });
                this.rows[index] = new_row;
            },
            removeRow(remove_order) {
                this.rows = this.rows.filter(function(row) {
                    return row.order !== remove_order
                });
            }
        },
        mounted() {
        }
    }
</script>