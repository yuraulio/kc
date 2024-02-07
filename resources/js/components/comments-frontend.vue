<template>
  <div class="" id="maincommentscontainer">
    <h2>{{ comments_title }}</h2>

    <template v-if="!loading">
      <div class="comment_block mb-3">
        <template v-if="comments.length">
          <template v-for="comment in comments">
            <div class="single_comment">
              <p class="single_comment_text">{{ comment.comment }}</p>
              <div class="single_comment_details">
                <div class="comment_author">
                  <img :src="comment.user.image" alt="" width="25" />
                  {{ comment.user.name }}
                </div>
                <div class="comment_date">{{ comment.diffForHumans }}</div>
              </div>
            </div>
          </template>
        </template>
        <template v-else>
          <div class="comment_block mt-4 mb-4">
            <div class="alert alert-info">{{ comments_no_comments }}</div>
          </div>
        </template>
      </div>

      <template v-if="comments.count > 500">
        <p>
          <em>{{ comments_limit }}</em>
        </p>
      </template>
    </template>

    <template v-else>
      <vue-loaders-ball-grid-beat color="#6658dd" scale="1" class="mt-4 text-center"></vue-loaders-ball-grid-beat>
    </template>

    <template v-if="user_id">
      <div class="add_comment_area">
        <h5 class="">{{ comments_add_title }}</h5>
        <div>
          <textarea
            v-model="message"
            maxlength="255"
            class="form-control"
            name="comment"
            required=""
            id="comment"
            :placeholder="comments_placeholder"
            rows="7"
          ></textarea>
        </div>

        <button @click="saveComment()" type="submit" class="btn btn--md btn--secondary">
          {{ comments_button }}
        </button>
      </div>
    </template>

    <template v-else>
      <div class="comment_block mt-4 mb-4">
        <div class="alert alert-info">{{ comments_not_logged_in }}</div>
      </div>
    </template>
  </div>
</template>

<script>
export default {
  props: {
    page_id: null,
    user_id: null,
    comments_title: {
      default: '',
    },
    comments_no_comments: {
      default: '',
    },
    comments_limit: {
      default: '',
    },
    comments_add_title: {
      default: '',
    },
    comments_button: {
      default: '',
    },
    comments_placeholder: {
      default: '',
    },
    comments_not_logged_in: {
      default: '',
    },
  },
  data() {
    return {
      comments: [],
      loading: true,
      message: '',
    };
  },
  methods: {
    getData() {
      this.loading = true;
      axios
        .get('/get-page-comments/' + this.page_id)
        .then((response) => {
          this.comments = response.data.data;
          this.loading = false;
        })
        .catch((error) => {
          console.log(error);
          this.loading = false;
        });
    },
    saveComment() {
      axios
        .post('/save-comment', {
          comment: this.message,
          page_id: this.page_id,
          user_id: this.user_id,
        })
        .then((response) => {
          if (response.status == 201) {
            this.comments.unshift(response.data.data);
            this.message = '';
          }
          this.loading = false;
        })
        .catch((error) => {
          console.log(error);
          this.loading = false;
        });
    },
  },
  mounted() {
    this.getData();
  },
};
</script>
