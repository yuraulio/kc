<template>
  <div class="row">
    <div class="col-sm-12 mt-3 mb-3">
      <div class="page-title-box">
        <h4 v-if="type != 'new'" class="page-title d-inline-block">Edit countdown</h4>
        <h4 v-else class="page-title d-inline-block">New Countdown</h4>
        <button
          v-if="type == 'new'"
          @click="add()"
          type="button"
          class="btn btn-soft-success waves-effect waves-light me-2 float-end"
          :disabled="loading"
        >
          <i v-if="loading" class="fas fa-spinner fa-spin"></i> Save
        </button>
        <button
          v-if="type == 'edit'"
          :disabled="loading"
          @click="edit()"
          type="button"
          class="btn btn-soft-success waves-effect waves-light me-2 float-end"
        >
          <i v-if="loading" class="fas fa-spinner fa-spin"></i> Save
        </button>
        <a href="/countdown" type="button" class="btn btn-soft-secondary waves-effect waves-light me-2 float-end"
          >Cancel</a
        >
        <div :key="'ck'" class="d-inline-block form-check form-switch mt-1 me-2 float-end" style="cursor: pointer">
          <input
            :key="'on'"
            @click="published = !published"
            :id="'cinput'"
            type="checkbox"
            class="form-check-input"
            name="color-scheme-mode"
            value="light"
            :for="'cinput'"
            :checked="published"
          />
          <label class="form-check-label" for="light-mode-check">Published</label>
        </div>
      </div>
    </div>

    <div class="col-lg-9">
      <template v-for="input in type == 'edit' ? config.editInputs : config.addInputs">
        <multiput
          :key="input.key"
          :keyput="input.key"
          :label="input.label"
          :options="input.options"
          :type="input.type"
          :size="input.size"
          :value="item[input.key]"
          :existing-value="item[input.key]"
          @inputed="inputed($event, input)"
          @selectAll="selectAll($event)"
          :multi="input.multi"
          :taggable="input.taggable"
          :fetch="input.fetch"
          :route="input.route"
          :placeholder="input.placeholder"
          ref="input"
        >
        </multiput>
        <div v-if="input.key == 'deliveries'" class="row">
          <div class="col-12">
            <multidropdown
              title="Chose if you want specific courses below"
              :multi="true"
              @updatevalue="update_event"
              :prop-value="event"
              :fetch="false"
              route="getEvents2"
              :data="events"
            ></multidropdown>
          </div>
        </div>
        <ul v-if="errors && errors[input.key]" class="parsley-errors-list filled" id="parsley-id-7" aria-hidden="false">
          <li class="parsley-required">{{ errors[input.key][0] }}</li>
        </ul>

        <template v-if="errors && input.key == 'subcategories'">
          <template v-for="(errorInput, key) in item.subcategories">
            <ul
              v-if="errors && errors[input.key + '.' + key]"
              class="parsley-errors-list filled"
              id="parsley-id-7"
              aria-hidden="false"
            >
              <li class="parsley-required">
                {{ fixError(errors[input.key + '.' + key][0], input.key + '.' + key, errorInput.title) }}
              </li>
            </ul>
          </template>
        </template>
      </template>
    </div>
    <div v-if="errors" class="row mt-3">
      <span class="text-danger" v-for="error in errors">{{ error[0] }}</span>
    </div>
  </div>
</template>

<script>
import multidropdown from './inputs/multidropdown.vue';
// import Tcedit from './tcdit.vue';
import gicon from './gicon.vue';
import slugify from '@sindresorhus/slugify';
import Multiput from './inputs/multiput.vue';
import _ from 'lodash';

export default {
  components: {
    multidropdown,
    gicon,
    Multiput,
  },
  props: {
    pageTitle: String,
    title: String,
    route: String,
    type: String,
    id: Number,
    data: {},
    config: {},
  },
  data() {
    // event, category_value einai oi timew toy countdown pou einai assigned
    return {
      event: null,
      errors: null,
      test: null,
      loading: false,
      loader: true,
      published: true,
      button_status: false,
      lodash: _,
      item: {},
      published_from_value: null,
      published_to_value: null,
      categories: [],
      events: [],
    };
  },
  methods: {
    inputed($event, key) {
      this.$set(this.item, $event.key, $event.data);
    },
    selectAll($event) {
      // data for save
      this.item.event = null;

      //data for selected render frontend
      this.event = null;

      if ($event.key == 'event') {
        this.item.event = Array.from(this.events);
        this.event = Array.from(this.events);
      }
    },
    update_event(value = []) {
      if (value == null) {
        value = [];
      }

      this.item.event = value;
    },
    setEvent(data) {
      if (typeof data.event !== 'undefined' && data.event.length != 0) {
        let del = [];

        data.event.forEach(function (event, index) {
          let obj = {
            id: event.id,
            title: event.title,
            delivery_id: event.delivery_id,
          };
          del.push(obj);
        });

        this.event = del;
      }
    },
    getEvents() {
      const self = this;
      axios
        .get('/api/getEventsList')
        .then((response) => {
          if (response.status == 200) {
            var data = response.data.data;

            self.events = data;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    add() {
      this.errors = null;
      this.loading = true;
      axios
        .post('/api/' + this.route, {
          title: this.item.title,
          content: this.item.content ? this.item.content : '',
          //categories: this.category_value,

          published_from: this.item.published_from,
          published_to: this.item.published_to,
          countdown_to: this.item.countdown_to,
          published: this.published,
          deliveries: this.item.deliveries,
          event: this.item.event,
          category: this.item.category,
          button_status: this.item.button_status ? this.item.button_status : this.button_status,
          button_title: this.item.button_title,
        })
        .then((response) => {
          if (response.status == 201) {
            this.item = { content: '' };

            this.$toast.success('Created Successfully!');

            this.config.addInputs = {};

            setTimeout(() => {
              window.location.href = '/countdown/' + response.data.data.id;
            }, 300);
          }
          this.loading = false;
        })
        .catch((error) => {
          console.log(error);
          this.errors = error.response.data.errors;
          this.loading = false;
          this.$toast.error('Failed to create. ' + this.errors[Object.keys(this.errors)[0]]);
        })
        .finally(() => {});
    },

    edit() {
      this.loading = true;

      this.item.published = this.published;

      // if(this.item.event !== undefined && this.item.event[0] !== undefined){
      //     this.item.event = this.item.event[0]
      // }

      axios
        .patch('/api/' + this.route + '/' + this.id, this.item)
        .then((response) => {
          if (response.status == 200) {
            this.route == 'categories' ? this.$emit('edited', response.data) : this.$emit('refreshcategories');
            // this.$emit('updatemode', 'list');
            this.$toast.success('Saved Successfully!');

            this.loading = false;
          }
        })
        .catch((error) => {
          console.log(error);
          this.loading = false;
          this.errors = error.response.data.errors;
          this.$toast.error('Failed to save. ' + this.errors[Object.keys(this.errors)[0]]);
        });
    },
    get() {
      if (this.id !== undefined) {
        axios
          .get('/api/' + this.route + '/' + this.id)
          .then((response) => {
            if (response.status == 200) {
              var data = response.data.data;

              this.item = data;

              this.setEvent(data);

              this.loader = false;
            }
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
  },
  computed: {
    deliveries() {
      return this.item.deliveries;
    },
  },
  created() {
    this.getEvents();
  },
  mounted() {
    if (this.data) {
      this.item = this.data;
      this.setEvent(this.data);
      this.loader = false;
    } else {
      this.get();
    }
  },
  watch: {
    deliveries(n, o) {
      let oi = o ? o.map((x) => x.id) : [];
      let ni = n ? n.map((x) => x.id) : [];
      let a = [];
      ni.forEach((x) => {
        const index = oi.indexOf(x);
        if (index > -1) {
          oi.splice(index, 1);
        } else {
          a.push(x);
        }
      });
      const self = this;
      let events = this.item.event ? Array.from(this.item.event) : [];
      if (oi?.length) {
        oi.forEach((deliveryId) => {
          events = events.filter((x) => x.delivery_id !== deliveryId);
        });
      }
      if (a.length) {
        a.forEach((deliveryId) => {
          const list = self.events.filter((x) => x.delivery_id === deliveryId);
          list.forEach((event) => {
            const index = events.findIndex((x) => x.id === event.id);
            if (index < 0) {
              events.push(event);
            }
          });
        });
      }
      this.event = events;
      this.$set(this.item, 'event', this.event);
    },
  },
};
</script>

<style scoped>
.form-check-input:checked {
  background-color: #28a745;
  border-color: #28a745;
}
</style>
