
<div class="comment_block">
@forelse($comments->reverse() as $comment)
    <div class="single_comment">
        <p class="single_comment_text">{!! nl2br(e($comment->comment))!!}</p>
        <div class="single_comment_details">
            <div class="comment_author">  <img src="/theme/assets/images/icons/user-profile-placeholder-image.png" alt="" width="25"> {{$comment->user->name }} </div>
            <div class="comment_date">  {{$comment->created_at->diffForHumans()}} </div>
        </div>
    </div>
    <!-- ./item -->
@empty
    <div class='alert alert-info'>No comments yet! Why don't you be the first?</div>
@endforelse

</div>

@if(count($comments)> config("binshopsblog.comments.max_num_of_comments_to_show",500) - 1)
    <p><em>Only the first {{config("binshopsblog.comments.max_num_of_comments_to_show",500)}} comments are
            shown.</em>
    </p>
@endif

<br>
