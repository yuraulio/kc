<div class="p-0 pb-4 pt-4 cms-rich-text-editor">

    @if($column->template->key == "instagram_component")
        @php
            $instagram_post = instagram_posts(8);

            $instagram_stories = instagram_stories(8);
            dd($instagram_stories);
        @endphp

        @if(count($instagram_post) != 0)
            @foreach($instagram_post as $post)

                @if($post->type == 'image')

                    <a target="_blank" href="{{$post->permalink}}"><img src="{{ $post->url }}" alt="A post from my instagram"></a>
                @elseif($post->type == 'video')
                    <video width="320" height="240" controls>
                        <source src="{{$post->url}}" type="">

                        Your browser does not support the video tag.
                    </video>
                @elseif($post->type == 'carousel')
                    {{'has Album here!!'}}

                @endif

            @endforeach
        @endif

        @if(count($instagram_stories) != 0)


            @foreach($instagram_stories as $post)
                @if($post->type == 'image')
                <a target="_blank" href="{{$post->permalink}}"><img src={{ $post->url }} alt="A post from my instagram"></a>
                @else
                <video width="320" height="240" controls>
                    <source src="{{$post->url}}" type="">

                    Your browser does not support the video tag.
                </video>
                @endif

            @endforeach
        @endif


    @endif
</div>
