{!! Form::model($content, ['method' => 'post', 'id' => 'sbt-content', 'class' => 'form-horizontal inherit_min_height']) !!}
    @include('admin.content_fields.required_fields', [])
    <div class="col-lg-9 col-md-8 col-sm-7 matched_cols">
        @include('admin.lang_versions.content.edit')
        <div class="tabs custContentMain">
            <a class="tabcbtn btn-post-post" href="#cmain" aria-controls="main" role="tab" data-toggle="tab">CONTENT</a>
            <a class="tabcbtn btn-post-post" href="#cbenefits" aria-controls="main" role="tab" data-toggle="tab">BENEFITS</a>
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
                        @include('admin.custom_fields.multi_dropzone', ["showLabel" => true, "content" => $content, "optionEntry" => "multi_dropzone", "label" => "Page Gallery"])
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="cmeta">
                    <div class="form-horizontal">
                        @include('admin.content_fields.slug', ["showLabel" => true])
                        @include('admin.content_fields.meta', ["showLabel" => true])
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="cbenefits">
                <div class="panel panel-default">
                           <div class="panel-heading">
                              <h4 class="panel-title"><a data-toggle="collapse" data-parent="#topicaccordion" href="#topiccollapse-1" class="collapsed" aria-expanded="false">Edit Title and Body</a></h4>
                           </div>
                           <div id="topiccollapse-1" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                              <div class="panel-body">
                                 <div class="fieldCont">
                                    <div class="form-group">
                                       <label class="col-sm-2 control-label no-bor"> Title </label>
                                       <div class="col-sm-10">
                                          <input id = 'benefits-title' name='titles[]' class= 'form-control' value="{{$content->titles()->where('category','benefits')->first()->title}}"  > 
                                       </div>
                                       <label class="col-sm-2 control-label no-bor"> Description </label>
                                       <div class="col-sm-10">
                                          <textarea id = 'benefits-body' name='titles[]' class= 'form-control'  > {{$content->titles()->where('category','benefits')->first()->body}} </textarea>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                    <div class="form-horizontal">
                        <h3>Benefits icon fields</h3>
                      
                       
                        <div class="icons-helper-wrap">
                            <div class="theforms">
                                <div class="fieldCont">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label no-bor"> Title </label>
                                    <div class="col-sm-10">
                                        <input id = 'Grow_your_business-title', class= 'form-control' value="{{$content->benefit()->where('category','Grow your business')->first()->title}}"  > 
                                    </div>
                                    <label class="col-sm-2 control-label no-bor"> Description </label>
                                    <div class="col-sm-10">
                                        <textarea id = 'Grow_your_business', class= 'form-control'  > {{$content->benefit()->where('category','Grow your business')->first()->description}} </textarea>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="iconhelper">
                                <img class="summary_ic" src="theme/assets/img/new_icons/benefits/book.svg" />
                            </div>
                        </div>
                        <div class="icons-helper-wrap">
                            <div class="theforms">
                                <div class="fieldCont">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label no-bor"> Title </label>
                                    <div class="col-sm-10">
                                        <input id = 'Learn_from_the_best-title', class= 'form-control' value="{{$content->benefit()->where('category','Learn from the best')->first()->title}}"  > 
                                    </div>
                                    <label class="col-sm-2 control-label no-bor"> Description </label>
                                    <div class="col-sm-10">
                                        <textarea id = 'Learn_from_the_best', class= 'form-control'  > {{$content->benefit()->where('category','Learn from the best')->first()->description}} </textarea>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="iconhelper">
                                <img class="summary_ic" src="theme/assets/img/new_icons/benefits/assessment.svg" />
                            </div>
                        </div>
                        <div class="icons-helper-wrap">
                            <div class="theforms">
                                <div class="fieldCont">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label no-bor"> Title </label>
                                    <div class="col-sm-10">
                                        <input id = 'Promote_Leadership-title', class= 'form-control' value="{{$content->benefit()->where('category','Promote Leadership')->first()->title}}"  > 
                                    </div>
                                    <label class="col-sm-2 control-label no-bor"> Description </label>
                                    <div class="col-sm-10">
                                        <textarea id = 'Promote_Leadership', class= 'form-control'  > {{$content->benefit()->where('category','Promote Leadership')->first()->description}} </textarea>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="iconhelper">
                                <img class="summary_ic" src="theme/assets/img/new_icons/benefits/mood.svg" />
                            </div>
                        </div>
                        <div class="icons-helper-wrap">
                            <div class="theforms">
                                <div class="fieldCont">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label no-bor"> Title </label>
                                    <div class="col-sm-10">
                                        <input id = 'Inspire_your_executives-title', class= 'form-control' value="{{$content->benefit()->where('category','Inspire your executives')->first()->title}}"  > 
                                    </div>
                                    <label class="col-sm-2 control-label no-bor"> Description </label>
                                    <div class="col-sm-10">
                                        <textarea id = 'Inspire_your_executives', class= 'form-control'  > {{$content->benefit()->where('category','Inspire your executives')->first()->description}} </textarea>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="iconhelper">
                                <img class="summary_ic" src="theme/assets/img/new_icons/benefits/event_available.svg" />
                            </div>
                        </div>
                        <div class="icons-helper-wrap">
                            <div class="theforms">
                                <div class="fieldCont">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label no-bor"> Title </label>
                                    <div class="col-sm-10">
                                        <input id = 'Be_competitive-title', class= 'form-control' value="{{$content->benefit()->where('category','Be competitive')->first()->title}}"  > 
                                    </div>
                                    <label class="col-sm-2 control-label no-bor"> Description </label>
                                    <div class="col-sm-10">
                                        <textarea id = 'Be_competitive', class= 'form-control'  > {{$content->benefit()->where('category','Be competitive')->first()->description}} </textarea>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="iconhelper">
                                <img class="summary_ic" src="theme/assets/img/new_icons/benefits/history.svg" />
                            </div>
                        </div>
                        <div class="icons-helper-wrap">
                            <div class="theforms">
                                <div class="fieldCont">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label no-bor"> Title </label>
                                    <div class="col-sm-10">
                                        <input id = 'Thrive-title' class= 'form-control' value="{{$content->benefit()->where('category','Thrive')->first()->title}}"  > 
                                    </div>
                                    <label class="col-sm-2 control-label no-bor"> Description </label>
                                    <div class="col-sm-10">
                                        <textarea id = 'Thrive'  class= 'form-control'  > {{$content->benefit()->where('category','Thrive')->first()->description}} </textarea>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="iconhelper">
                                <img class="summary_ic" src="theme/assets/img/new_icons/benefits/cloud_download.svg" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-5 matched_cols side-back">
        <div class="post-info custContentSide">
            @include('admin.content_fields.status', [])
            @include('admin.content_fields.info', [])
            @include('admin.content_fields.static_tpl_dropdown', [])
            @include('admin.content_fields.static_tpl_group', [])
            @include('admin.content_fields.featured_media', [])
        </div>
    </div>
{!! Form::close() !!}
<script type="text/javascript">
$(function() {
    loadContentFor(contentObj.scope, "get_featured");
});

$('#Thrive').redactor({
       clickToEdit: true,
       clickToCancel: { title: 'Cancel' }
   });
   
   $('#Grow_your_business').redactor({
       clickToEdit: true,
       clickToCancel: { title: 'Cancel' }
   });
   
   $('#Learn_from_the_best').redactor({
       clickToEdit: true,
       clickToCancel: { title: 'Cancel' }
   });
   
   $('#Promote_Leadership').redactor({
       clickToEdit: true,
       clickToCancel: { title: 'Cancel' }
   });
 ;
   
   $('#Inspire_your_executives').redactor({
       clickToEdit: true,
       clickToCancel: { title: 'Cancel' }
   });
   $('#Be_competitive').redactor({
       clickToEdit: true,
       clickToCancel: { title: 'Cancel' }
   });

   $('#benefits-body').redactor({
       clickToEdit: true,
       clickToCancel: { title: 'Cancel' }
   });

</script>
