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
           Row:
        </div>
        <div class="col-5">
          <input type="text" class="form-control" :placeholder="'Enter title'" v-model="row.description">
        </div>
        <div class="col-3">
            Columns:
        </div>
        <div class="col-3">
            <select v-model="columnsNumber" class="form-select my-1 my-md-0">
                <option selected="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </div>
    </div>

    <br>

    <div class="row">
            <div v-for="column in row.columns" :class="'col-md-' + (12/columnsNumber)">
                <component-field
                    class="border"
                    required="true"
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
            "propValue": function() {
                this.row = this.propValue;
            },
            "columnsNumber": function() {
                this.row.columns = [];
                for (let i = 0; i < this.columnsNumber; i++) {
                    this.row.columns.push({
                        order: i,
                        component: null,
                    });
                }
            },
            "order": function() {
                console.log("test");
                // this.row.order = this.order;
            },
            "row": {
                handler: function() {
                    this.$emit('updatevalue', this.row);
                },
                deep: true 
            }
        },
        methods: {
            
        },
        mounted() {
            this.row.order = this.order;
        }
    }
</script>