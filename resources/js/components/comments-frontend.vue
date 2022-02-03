<template>

<div  class="" id="maincommentscontainer">

    <h2 >Comments</h2>

    <template v-if="!loading">

        <div class="comment_block mb-3">
            <template v-if="comments">
                <template v-for="comment in comments">
                    <div class="single_comment">
                        <p class="single_comment_text">{{comment.comment}}</p>
                        <div class="single_comment_details">
                            <div class="comment_author">
                                <!-- <img src="{{ get_profile_image(Auth::user()->image) ?? '/theme/assets/images/icons/user-profile-placeholder-image.png' }}" alt="" width="25"> -->
                                <img src="/theme/assets/images/icons/user-profile-placeholder-image.png" alt="" width="25"> 
                                {{comment.user}}
                            </div>
                            <div class="comment_date">{{comment.diffForHumans}}</div>
                        </div>
                    </div>
                </template>
            </template>
            <template v-else>
                <div class="comment_block mt-4 mb-4">
                    <div class="alert alert-info">No comments yet! Why don't you be the first?</div>
                </div>
            </template>
        </div>

        <template v-if="comments.count > 500">
            <p><em>Only the first 500 comments are shown.</em></p>
        </template>

    </template>

    <template v-else>
        <vue-loaders-ball-grid-beat	 color="#6658dd" scale="1" class="mt-4 text-center"></vue-loaders-ball-grid-beat	>
    </template>

    <template v-if="user_id">
        <div class="add_comment_area">
            <h5 class="">Add a comment</h5>
                <div>
                    <textarea v-model="message" maxlength="255" class="form-control" name="comment" required="" id="comment" placeholder="Write your comment here" rows="7"></textarea>
                </div>

                <button @click="saveComment()" type="submit" class="btn btn--md btn--secondary">Add Comment</button>
        </div>
    </template>

    <template v-else>
        <div class="comment_block mt-4 mb-4">
            <div class="alert alert-info">You must be logged in to comment.</div>
        </div>
    </template>

</div>

</template>

<script>
    export default {
        props: {
            page_id: null,
            user_id: null,
        },
        data() {
            return {
                comments: [],
                loading: true,
                message: "",
            }
        },
        methods: {
            getData(){
                this.loading = true;
                axios.get('/get-page-comments/' + this.page_id)
                    .then((response) => {
                        this.comments = response.data.data;
                        this.loading = false;
                    })
                    .catch((error) => {
                        console.log(error)
                        this.loading = false;
                    });
            },
            saveComment(){
                axios.post('/save-comment', {
                    comment: this.message,
                    page_id: this.page_id,
                    user_id: this.user_id, 
                })
                .then((response) => {
                    if (response.status == 201){
                        this.comments.unshift(response.data.data)
                        this.message = "";
                    }
                    this.loading = false;
                })
                .catch((error) => {
                    console.log(error)
                    this.loading = false;
                });
            }
        },
        mounted() {
            this.getData();
        }
    }
</script>
