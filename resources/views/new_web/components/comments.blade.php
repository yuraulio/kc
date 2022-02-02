<div style="margin-top: 20px; margin-bottom: 20px;" class="container blogx-container" id="maincommentscontainer">

    <h2 >Comments</h2>

    <div class="comment_block mb-3">
 
        @foreach ($comments as $comment)
            <div class="single_comment">
                <p class="single_comment_text">{{$comment->comment}}</p>
                <div class="single_comment_details">
                    <div class="comment_author">  
                        <img src="{{ get_profile_image(Auth::user()->image) ?? '/theme/assets/images/icons/user-profile-placeholder-image.png' }}" alt="" width="25"> 
                        {{ucwords($comment->user->firstname) . " " . ucwords($comment->user->lastname)}}
                    </div>
                    <div class="comment_date">{{$comment->created_at->diffForHumans()}}</div>
                </div>
            </div>
        @endforeach

    </div>

    @if (count($comments) > 500)
        <p><em>Only the first 500 comments are shown.</em></p>
    @endif

    <div class="add_comment_area">
        <h5 class="">Add a comment</h5>
        <form method="post" action="/comments/store">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div>
                <textarea maxlength="255" class="form-control" name="comment" required="" id="comment" placeholder="Write your comment here" rows="7"></textarea>
            </div>

            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="page_id" value="{{ $page_id }}">

            <button type="submit" class="btn btn--md btn--secondary">Add Comment</button>

        </form>
    </div>

</div>