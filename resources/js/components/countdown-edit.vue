<style scoped></style>

<template>
    <div>
        <countdowneditable
            title="true"
            category="true"
            type="edit"
            route="countdown"
            page-title="Edit Countdown"
            :data="item"
            :id="countdownId"
            :uuid="null"
            @changeMode="changeMode"
            :key="NaN"
            :config="config"
        ></countdowneditable>
    </div>
</template>

<script>
import countdowneditable from './countdowneditable.vue';
import countdownConfig from '../table_configs/countdownTable';

export default {
    components: {
        countdowneditable,
        countdownConfig,
    },
    props: {
        countdownId: Number,
    },
    data() {
        return {
            item: {},
            content: null,
            simple: true,
            config: countdownConfig,
        };
    },
    methods: {
        getPage() {
            if (this.countdownId) {
                axios
                    .get('/api/countdown/' + this.countdownId)
                    .then((response) => {
                        if (response.status == 200) {
                            this.item = response.data.data;
                            //this.content = JSON.parse(this.page.content);
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        changeMode(page) {
            this.page = page;
            this.content = this.page.content;
            this.simple = !this.simple;
        },
    },
    mounted() {
        this.getPage();
    },
};
</script>
