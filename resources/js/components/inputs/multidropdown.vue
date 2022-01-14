<template>

<div class="mb-3">
    <label for="projectname" class="form-label">{{title}} <span v-if="required">*</span> </label>

    <!-- <select @change="$emit('updatevalue', value)" v-model="value" class="form-select my-1 my-md-0">
        <option v-for="item in list" :value="item.id">{{item.title}}</option>
    </select> -->

    <multiselect
        deselectLabel=""
        selectLabel=""
        @input="inputed"
        v-model="value"
        placeholder="Pick some"
        :multiple="multi"
        label="title"
        track-by="id"
        :options="list"
    ></multiselect>
</div>

</template>

<script>
    export default {
        props: {
            title: String,
            propValue: "",
            required: false,
            route: String,
            multi: {
                default: true
            }
        },
        data() {
            return {
                value: null,
                list: [],
            }
        },
        watch: {
            "propValue": function() {
                this.value = this.propValue;
            }
        },
        methods: {
            inputed($event) {
                this.$emit('updatevalue', $event)
            },
            get(){
                axios
                .get('/api/' + this.route)
                .then((response) => {
                    if (response.status == 200){
                        var data = response.data.data;
                        this.list = data;
                        console.log(data)
                    }
                })
                .catch((error) => {
                    console.log(error)
                });
            }
        },
        mounted() {
            this.get();
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style>
.multiselect__tag {
    background: #6658dd !important;
}
.multiselect__option--highlight {
    background: #6658dd !important;
}
.multiselect__tags {

    font-size: 14px;
    font-size: 0.875rem;
    font-weight: 400;
    line-height: 1.5;
    color: #6c757d;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: 0.2rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.multiselect__tag-icon:hover {
    background: #4535cb !important;
}
.multiselect__tag-icon::after {
    color: white !important;
}
</style>
