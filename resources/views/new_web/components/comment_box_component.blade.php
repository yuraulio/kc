<div class="pb-4 pt-4">
    <comments-frontend
        page_id="{{ $page_id }}"
        user_id="{{Auth::user()->id ?? null}}"
    >
    </comments-frontend>
</div>