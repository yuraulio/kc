@php
    if (isset($dynamic_page_data) && $column->template->dynamic) {
        $sections = $dynamic_page_data["sections"] ?? null;
        $section_fullvideo = $dynamic_page_data["section_fullvideo"] ?? null;
        $event = $dynamic_page_data["event"] ?? null;
        $estatus = $event->status ?? null;
    } else {
        $youtube = [];
        foreach ($column->template->inputs as $input){
            $youtube[$input->key] = $input->value ?? "";
        }
    }
@endphp

@if (isset($dynamic_page_data) && $column->template->dynamic)
    @if($estatus == App\Model\Event::STATUS_OPEN || $estatus == App\Model\Event::STATUS_SOLDOUT)
        @if(isset($section_fullvideo) && $section_fullvideo->body != '')
            <div class="video-wrapper mb-5 mt-5">
                <div class="responsive-fb-video">
                    {!! $section_fullvideo->body !!}
                </div>
            </div>
        @endif
    @endif
@else
    <div class="text-center mb-5 mt-5">
        <iframe
            id="{{ $column->id }}"
            width="{{ $youtube["youtube_full_width"] ? "100%" : ($youtube["youtube_width"] ? $youtube["youtube_width"] : '100%') }}"
            height="{{ $youtube["youtube_height"] ? $youtube["youtube_height"] : '600' }}"
            src=""
            title="YouTube video player"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
        ></iframe>
    </div>

    <script type="application/javascript">
        document.addEventListener("DOMContentLoaded", function(event) {
            function validURL(str) {
                var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
                    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
                    '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
                    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
                    '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
                    '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
                return !!pattern.test(str);
            }
            function getYoutubeVideoCode(str) {
                if (validURL(str)) {
                    return 'https://www.youtube.com/embed/' + getUrlVars(str)["v"];
                } else {
                    return 'https://www.youtube.com/embed/' + str;
                }
            }
            function getUrlVars(url) {
                var vars = {};
                var parts = url.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                    vars[key] = value;
                });
                return vars;
            }

            var id = "{{ $column->id }}";
            var code = getYoutubeVideoCode("{{ $youtube["youtube_embed"] ?? "" }}");
            document.getElementById(id).setAttribute('src', code);
        });
    </script>
@endif
