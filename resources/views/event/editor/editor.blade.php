<div id="{{$keyinput}}" class="editor-old-admin bootstrap-classes ubold mt-5 mb-5 pl-lg-4">

    {{--@if(isset($plugins) && isset($toolbar))
    <editor-for-old-admin
        keyput="{{$keyinput}}"
        editorData="{{$data}}"
        value="{{$data}}"
        inputname="{{$inputname}}"

        plugins="{{$plugins}}"
        toolbar="{{$toolbar}}"
    ></editor-for-old-admin>
    @else
    <editor-for-old-admin
        keyput="{{$keyinput}}"
        editorData="{{$data}}"
        value="{{$data}}"
        inputname="{{$inputname}}"
    ></editor-for-old-admin>
    @endif--}}

    <editor-for-old-admin
        keyput="{{$keyinput}}"
        editorData="{{$data}}"
        value="{{$data}}"
        inputname="{{$inputname}}"
    ></editor-for-old-admin>

</div>

@push('js')
@endpush
