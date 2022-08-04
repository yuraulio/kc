@php
    $coments = [];
    foreach ($column->template->inputs as $input){
        $coments[$input->key] = $input->value ?? "";
    }
@endphp

<div class="pb-4 pt-4">
    <comments-frontend
        page_id="{{ $page_id }}"
        user_id="{{Auth::user()->id ?? null}}"
        comments_title="{{ $coments["comments_title"] }}"
        comments_no_comments="{{ $coments["comments_no_comments"] }}"
        comments_limit="{{ $coments["comments_limit"] }}"
        comments_add_title="{{ $coments["comments_add_title"] }}"
        comments_button="{{ $coments["comments_button"] }}"
        comments_placeholder="{{ $coments["comments_placeholder"] }}"
        comments_not_logged_in="{{ $coments["comments_not_logged_in"] }}"
    >
    </comments-frontend>
</div>