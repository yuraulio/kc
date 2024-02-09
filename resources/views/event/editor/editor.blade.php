<div id="{{$keyinput}}" class="editor-old-admin bootstrap-classes ubold mt-5 mb-5 pl-lg-4">

    <editor-for-old-admin
        keyput="{{$keyinput}}"
        editorData="{{$data}}"
        value="{{$data}}"
        inputname="{{$inputname}}"
        v-bind:variables="{{ empty($variables) ? '{}' : json_encode($variables) }}"
    ></editor-for-old-admin>

</div>
