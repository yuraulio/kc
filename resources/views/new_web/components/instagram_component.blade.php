<div class="p-0 pb-4 pt-4 cms-rich-text-editor">
    @foreach ($column->template->inputs as $input)
        @if($input->key == "instagram_profile")
            @php
                $instagram_feed = instagram_feed($input->value, 8);

            @endphp

            @if(count($instagram_feed) != 0)

            @foreach($instagram_feed as $post)
                <img src={{ $post->url }} alt="A post from my instagram">
            @endforeach
            @endif


        @endif
    @endforeach
</div>
