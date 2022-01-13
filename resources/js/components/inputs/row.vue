<style scoped >
    .spacing {
        margin: 0px;
        padding: 10px;
    }
    .border {
        border: 1px solid #ced4da;
        border-radius: 0.2rem
    }
    .background {
        background-color: #f5f6f8;
    }
</style>

<template>

<div class="border spacing mb-3 background">
    <div class="row">
        <div class="col-1">
           <span class="mt-1 d-flex">Row:</span>
        </div>
        <div class="col-5">
          <input type="text" class="form-control" :placeholder="'Enter title'" v-model="row.description">
        </div>
        <div class="col-3">
            <span class="mt-1 d-flex">Columns:</span>
        </div>
        <div class="col-2">
            <select @change="changeColumns()" v-model="columnsNumber" class="form-select my-1 my-md-0">
                <option selected="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </div>
        <div class="col-1 text-end">
            <button @click="removeRow()" type="button" class="btn btn-danger waves-effect waves-light"><i class="fe-x"></i></button>
        </div>
    </div>

    <br>

    <div class="row">
            <div v-for="column in row.columns" :class="'col-md-' + (12/columnsNumber)">
                <component-field
                    class="border"
                    required="true"
                    @updatecomponent="updatecomponent"
                    :order="column.order"
                    :prop-value="column.component"
                ></component-field>
            </div>
    </div>

</div>

</template>

<script>
    export default {
        props: {
            propValue: null,
            required: false,
            order: 0,
        },
        data() {
            return {
                columnsNumber: "1",
                row: {
                    order: 0,
                    description: "",
                    columns: [{
                        order: 0,
                        component: null,
                    }],
                },
            }
        },
        watch: {
            "row": {
                handler: function() {
                    this.$emit('updatevalue', this.row);
                },
                deep: true 
            }
        },
        methods: {
            updatecomponent(component) {
                var index = this.row.columns.findIndex(function(column) {
                    return column.order == component[1];
                });
                this.row.columns[index].component = component[0];
            },
            changeColumns(){
                this.row.columns = [];
                for (let i = 0; i < this.columnsNumber; i++) {
                    this.row.columns.push({
                        order: i,
                        component: "Select component",
                    });
                }
            },
            removeRow(){
                this.$emit('removeRow', this.order);
            }
        },
        mounted() {
            this.row.order = this.order;
            if (this.propValue) {
                this.row = this.propValue;
                this.columnsNumber = this.row.columns.length;
            }
        }
    }
</script>