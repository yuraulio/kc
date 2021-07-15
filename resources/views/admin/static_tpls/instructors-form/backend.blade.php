{!! Form::model($content, ['method' => 'post', 'id' => 'sbt-content', 'class' => 'form-horizontal inherit_min_height']) !!}
    @include('admin.content_fields.required_fields', [])
    <div class="col-lg-9 col-md-8 col-sm-7 matched_cols">
        @include('admin.lang_versions.content.edit')
        <div class="tabs custContentMain">
            <a class="tabcbtn btn-post-post" href="#cmain" aria-controls="main" role="tab" data-toggle="tab">CONTENT</a>
            <a class="tabcbtn btn-post-meta" href="#cmeta" aria-controls="meta" role="tab" data-toggle="tab">META</a>
            <a class="contentTypeHeader pull-right">
                {{ $custType['title'] }}
            </a>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="cmain">
                    <div class="form-horizontal">
                        @include('admin.content_fields.title', ["showLabel" => true])
                        @include('admin.content_fields.subtitle', ["showLabel" => true])
                        @include('admin.content_fields.summary', ["showLabel" => true])

                        @include('admin.content_fields.body', ["showLabel" => true])
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="cmeta">
                    <div class="form-horizontal">
                        @include('admin.content_fields.slug', ["showLabel" => true])
                        @include('admin.content_fields.meta', ["showLabel" => true])
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-5 matched_cols side-back">
        <div class="post-info custContentSide">
            @include('admin.content_fields.status', [])
            @include('admin.content_fields.static_tpl_dropdown', [])
            @include('admin.content_fields.static_tpl_group', [])
            @include('admin.content_fields.info', [])
            @include('admin.content_fields.featured_media', [])
        </div>
    </div>
{!! Form::close() !!}
<script type="text/javascript">
$(function() {
    loadContentFor(contentObj.scope, "get_featured");
});
</script>
