
<div class="row align-items-center">
    <div class="col-8">
        <h3 class="mb-0">{{ __('Sections') }}</h3>
        <p class="text-sm mb-0">
                {{ __('This is an example of Section management.') }}
            </p>
    </div>
    @can('create', App\Model\User::class)
        <div class="col-4 text-right">
        <button data-toggle="modal" data-target="#sectionModal" class="btn btn-sm btn-primary">{{ __('Add section') }}</button>
        </div>
    @endcan
</div>

<div class="table-responsive py-4">
    <table class="table align-items-center table-flush"  id="datatable-basic">
        <thead class="thead-light">
            <tr>
                <th scope="col">{{ __('Section') }}</th>
                <th scope="col">{{ __('Title') }}</th>
                <th scope="col">{{ __('Description') }}</th>
                <th scope="col">{{ __('Created at') }}</th>
                <th scope="col"></th>
            </tr>
        </thead>

        <tbody class="section-body">
            @if($model->sections)
                @foreach ($event->sections as $section)
                    <tr id="section_{{$section->id}}">
                        <td id="section-{{$section->id}}"><a class="edit_btn_section1" href="#">{{ $section->section }}</td>
                        <td id="section-title-{{$section->id}}">{{ $section->title }}</td>
                        <td id="section-desc-{{$section->id}}">{{ $section->description }}</td>

                        <td>{{ date_format($section->created_at, 'Y-m-d' ) }}</td>

                            <td class="text-right">

                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" data-toggle="modal" data-target="#editSectionModal" data-id="{{$section->id}}" data-section="{{$section->section}}" data-desc="{{$section->description}}" data-title="{{$section->title}}">{{ __('Edit') }}</a>
                                        </div>
                                    </div>

                            </td>

                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
<!-- Modal -->
<div class="modal fade" id="sectionModal" tabindex="-1" role="dialog" aria-labelledby="sectionModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="sectionModalLabel">Create Section</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <h6 class="heading-small text-muted mb-4">{{ __('Section information') }}</h6>
            <div class="pl-lg-4">
            <form id="section-form" >
               <div class="form-group{{ $errors->has('section') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="input-section">{{ __('Section') }}</label>
                  <input type="text" name="section" id="input-section" class="form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" placeholder="{{ __('Section') }}" value="{{ old('section') }}" required autofocus>
                  @include('alerts.feedback', ['field' => 'section'])
               </div>
               <div class="form-group{{ $errors->has('title-section') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="input-title-section">{{ __('Title') }}</label>
                  <input type="text" name="title-section" id="input-title-section" class="form-control{{ $errors->has('title-section') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title-section') }}" autofocus>
                  @include('alerts.feedback', ['field' => 'title-section'])
               </div>
               <div class="form-group{{ $errors->has('description-section') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="input-description-section">{{ __('Description') }}</label>
                  <input type="text" name="description-section" id="input-description-section" class="form-control{{ $errors->has('description-section') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" value="{{ old('description-section') }}" autofocus>
                  @include('alerts.feedback', ['field' => 'description-section'])
               </div>
            </form>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
            <button type="button" id="save-section" class="btn btn-primary">Save changes</button>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="editSectionModal" tabindex="-1" role="dialog" aria-labelledby="sectionModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="sectionModalLabel">Edit Section</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <h6 class="heading-small text-muted mb-4">{{ __('Section information') }}</h6>
            <div class="pl-lg-4">
            <form id="section-form-edit">
               <div class="form-group{{ $errors->has('section') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="edit-section">{{ __('Name') }}</label>
                  <input type="text" name="section" id="edit-section" class="form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('section') }}" required autofocus>
                  @include('alerts.feedback', ['field' => 'section'])
               </div>
               <div class="form-group{{ $errors->has('title-section') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="edit-title-section">{{ __('Title') }}</label>
                  <input type="text" name="title-section" id="edit-title-section" class="form-control{{ $errors->has('title-section') ? ' is-invalid' : '' }}" placeholder="{{ __('Title') }}" value="{{ old('title-section') }}" autofocus>
                  @include('alerts.feedback', ['field' => 'title-section'])
               </div>
               <div class="form-group{{ $errors->has('description-section') ? ' has-danger' : '' }}">
                  <label class="form-control-label" for="edit-description-section">{{ __('Section') }}</label>
                  <input type="text" name="description-section" id="edit-description-section" class="form-control{{ $errors->has('description-section') ? ' is-invalid' : '' }}" placeholder="{{ __('Section') }}" value="{{ old('description-section') }}" autofocus>
                  @include('alerts.feedback', ['field' => 'description-section'])
               </div>
               <input type="text" id="section-id"  value="" hidden>
            </form>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
            <button type="button" id="edit-section-btn" class="btn btn-primary">Save changes</button>
         </div>
      </div>
   </div>
</div>


@push('css')
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
@endpush

@push('js')

    <script>

    $(document).on('click',".edit_btn_section1",function(){
        //alert('asd')
        $(this).parent().parent().find('a')[2].click()
        //$(this).parent().parent().find('.dropdown-item').click()
    })

        // $(document).on('shown.bs.modal', '#editSectionModal',function(e) {
        //     //e.preventDefault()
        //     var link  = e.relatedTarget,
        //             modal    = $(this),
        //         id = e.relatedTarget.dataset.id
        //         //name = e.relatedTarget.dataset.name,
        //         //description =e.relatedTarget.dataset.description;
        //         name = $("#name-"+id).text();
        //         description = $("#desc-"+id).text();

        //         modal.find("#sectionModalLabel").val(name)

        //     modal.find("#edit-name").val(name);
        //     modal.find("#edit-description1").val(description);
        //     modal.find("#section-id").val(id)

        // });
    </script>
    <script>
        $(document).on('click',"#save-section",function(){

            let modelType = "{{addslashes ( get_class($model) )}}";
            let modelId = "{{ $model->id }}";

                $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{route("section.store")}}',
                    data: {'section':$('#input-section').val(), 'title':$('#input-title-section').val(),'description':$('#input-description-section').val(),'model_type':modelType,'model_id':modelId},
                    success: function (data) {
                //console.log(data);
                let section = data.section;
                let newSection =
                `<tr id="section_`+section['id']+`">` +
                `<td id="section-` + section['id'] +`"><a class="edit_btn_section1" href="#">` + section['section'] + `</td>` +
                `<td id="section-title-` + section['id'] +`">` + section['title'] + `</td>` +
                `<td id="section-desc-`+section['id']+`">` + section['description'] + `</td>` +
                `<td>` + section['created_at'] + `</td>` +

            `<td class="text-right">
                        <div class="dropdown">
                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a class="dropdown-item" data-toggle="modal" data-target="#editSectionModal" data-id="` + section['id'] + `" data-section="`+section['section'] + `" data-title-section="`+section['title'] +`" data-description="`+ section['description'] + `">{{ __('Edit') }}</a>

                        </div>
                        </div>
                    </td>

                </tr>`;


                $(".section-body").append(newSection);
                $(".close-modal").click();
                $("#success-message p").html(data.success);
                $("#success-message").show();
                $('#section-form').trigger('reset');
                    }
                })
        });
    </script>


    <script>
    $(document).on('click',"#edit-section-btn",function(){

        $sectionId = $("#section-id").val()
        $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'put',
                url: '/admin/section/' + $sectionId,
                data: {'section':$('#edit-section').val(),'title':$('#edit-title-section').val(),'description':$('#edit-description-section').val()},
                success: function (data) {

                    let section = data.section;

                    $("#section-"+section['id']).html(`<a class="edit_btn_section1" href="#">`+section['section'])
                    $("#section-title-"+section['id']).html(section['title'])
                    $("#section-desc-"+section['id']).html(section['description'])
                    $('#section-form-edit').trigger('reset');
                    $(".close-modal").click();

                    $("#success-message p").html(data.success);
                    $("#success-message").show();

                },
                error: function() {
                    //console.log(data);
                }
        });
    })
    </script>
    <script>

    $(document).on('shown.bs.modal', '#editSectionModal',function(e) {
        e.preventDefault()


        var link  = e.relatedTarget,
            modal    = $(this),
            id = e.relatedTarget.dataset.id

            section = $("#section-"+id).text(),

            title = $("#section-title-"+id).text();
            console.log(section)
            description = $("#section-desc-"+id).text();

            modal.find("#sectionModalLabel").val(title)

        modal.find("#edit-section").val(section);
        modal.find("#edit-title-section").val(title);
        modal.find("#edit-description-section").val(description);
        modal.find("#section-id").val(id)

    });





    </script>
@endpush
