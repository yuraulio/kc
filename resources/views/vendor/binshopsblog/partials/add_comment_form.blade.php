<div class='add_comment_area'>
    <h5 class=''>Add a comment</h5>
    <form method='post' action='{{route("binshopsblog.comments.add_new_comment",[app('request')->get('locale'),$post->slug])}}'>
        @csrf

        <div>
            <textarea
                    class="form-control"
                    name='comment'
                    required
                    id="comment"
                    placeholder="Write your comment here"
                    rows="7">{{old("comment")}}
            </textarea>
        </div>


        @if($captcha)
            {{--Captcha is enabled. Load the type class, and then include the view as defined in the captcha class --}}
            @include($captcha->view())
        @endif

        <button type="submit" class="btn btn--md btn--secondary">Add Comment</button>

    </form>
</div>
