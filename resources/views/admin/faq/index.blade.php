
<div class="row align-items-center">
    <div class="col-8">
        <h3 class="mb-0">{{ __('Faqs') }}</h3>
        <p class="text-sm mb-0">
                {{ __('') }}
            </p>
    </div>

        {{--<div class="col-4 text-right">
        <button data-toggle="modal" data-target="#faqModal" class="btn btn-sm btn-primary">{{ __('Assign Faqs') }}</button>
        </div>--}}

</div>

<div class="col-12 mt-2">
    @include('alerts.success')
    @include('alerts.errors')
</div>


<?php

   $id = isset($sections['questions'][0]) ? $sections['questions'][0]['id'] : '';
   $tab_title = isset($sections['questions'][0]) ? $sections['questions'][0]['tab_title'] : '' ;
   $title = isset($sections['questions'][0]) ? $sections['questions'][0]['title'] : '' ;
   $visible = isset($sections['questions'][0]) ? $sections['questions'][0]['visible'] : false ;

?>


<div class="form-group">

   <input hidden name="sections[questions][id]" value="{{$id}}">

   <label class="form-control-label" for="input-title">{{ __('Tab Title') }}</label>
   <input type="text" name="sections[questions][tab_title]" class="form-control" placeholder="{{ __('Tab Title') }}" value="{{ old("sections[questions][tab_title]", $tab_title) }}" autofocus>
   <label class="form-control-label" for="input-title">{{ __('H2 Title') }}</label>
   <input type="text" name="sections[questions][title]" class="form-control" placeholder="{{ __('H2 Title') }}" value="{{ old("sections[questions][title]", $title) }}" autofocus>


   <label class="form-control-label" for="input-method">{{ __('Visible') }}</label>
   <div style="margin: auto;" class="form-group">

       <label class="custom-toggle enroll-toggle visible">
           <input type="checkbox"  name="sections[questions][visible]" @if($visible) checked @endif>
           <span class="custom-toggle-slider rounded-circle" data-label-off="no visible" data-label-on="visible"></span>
       </label>

   </div>


</div>


<div class="accordion"  id="accordionTopicMain1">

    @foreach ($event->getFaqsByCategoryEvent() as $key => $faqs)


        <div class="card">
            <div class="card-header" id="cattt_{{\Illuminate\Support\Str::slug($key)}}" data-toggle="collapse" data-target="#catt_{{\Illuminate\Support\Str::slug($key)}}" aria-expanded="false" >
                <h5 class="mb-0">{{$key}}</h5>
            </div>

            <div id="catt_{{\Illuminate\Support\Str::slug($key)}}" class="collapse" aria-labelledby="catt_{{\Illuminate\Support\Str::slug($key)}}" data-parent="#accordionTopicMain1">
                <div class="card-body">
                    <div class="table-responsive py-4">
                        <table class="table align-items-center table-flush"  id="datatable-basic9">
                            <thead class="thead-light">
                                <tr>
                                <th scope="col">{{ __('Title') }}</th>
                                <th scope="col">{{ __('Operations') }}</th>
                                </tr>
                            </thead>
                            <tbody id="faq-body" class="faq-order">
                            @foreach($faqs as $faq)
                                <tr id="faq-{{$faq['id']}}" data-id="{{$faq['id']}}" class="faq-list">
                                    <td>{{ $faq['question'] }}</td>


                                    <td>
                                        @if(!in_array($faq['id'],$eventFaqs))
                                            <button class="btn btn-primary assing" data-faq = '{{$faq["id"]}}' type="button">Assign</button>
                                        @else
                                            <button class="btn btn-primary unsing" data-faq = '{{$faq["id"]}}' type="button">Unsign</button>
                                        @endif
                                    </td>


                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>


        </div>

    @endforeach
</div>


<!-- Modal -->
{{--<div class="modal fade" id="faqModal" tabindex="-1" role="dialog" aria-labelledby="faqModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="faqModalLabel">Assign Faq</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <h6 class="heading-small text-muted mb-4">{{ __('Faq information') }}</h6>
            <div class="pl-lg-4">
            <form id="faq-form" >
                <div class="pl-lg-4">
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Select Faq</label>
                        <select class="form-control" id="faqFormControlSelect">
                            <option>-</option>
                        </select>
                    </div>
                </div>
            </form>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
            <button type="button" id="save-faq" class="btn btn-primary">Save changes</button>
         </div>
      </div>
   </div>
</div>--}}
@push('js')
    <script>
        $(document).on('shown.bs.modal', '#faqModal',function(e) {

            let modelType = "{{addslashes ( get_class($model) )}}";
            let modelId = "{{ $model->id }}";

            $('#faqFormControlSelect option').each(function(key, value) {
                        $(value).remove()
                });

                $('#faqFormControlSelect').append(`<option>-</option>`)

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: '/admin/faqs/fetchAllFaqs',
                data:{'model_type':modelType,'model_id':modelId},
                success: function (data) {
                    let faq = data.faqs


                    $.each( faq, function( key, value ) {
                        row =`
                            <option value="${value.id}">${value.name}</option>
                        `
                        $('#faqFormControlSelect').append(row)
                    });
                }
            });
        })

        $(document).on('click', '#save-faq',function(e) {

                let modelType = "{{addslashes ( get_class($model) )}}";
                let modelId = "{{ $model->id }}";

                var selected_option = $('#faqFormControlSelect').val();


                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: '{{route("faqs.store_event")}}',
                    data: {'faqs_id':$('#faqFormControlSelect').val(), 'model_type':modelType,'model_id':modelId},
                    success: function (data) {
                        let faq = data.data

                        $('#faq-body tr').remove();

                        $.each(faq, function(key,val){

                            let newFaq =
                            `<tr id="faq-`+val.id+`">` +
                            `<td id="title-` + val.id +`">` + val.title + `</td>` +

                            `<td id="category-` + val.id +`">` + val.category[0]['name'] + `</td>` +

                            ` <td><button class="btn btn-primary assing" data-faq = '` + val.id +`' type="button">Unsign</button></td>` +
                            `

                            </tr>`;

                            $("#faq-body").append(newFaq);
                        })

                        $(".close-modal").click();
                        $("#success-message p").html(data.success);
                        $("#success-message").show();


                    }
                });

                })

                $(document).on('click', '.assing',function(e) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'get',
                        url: '/admin/faqs/assign-event/' + "{{$event->id}}" +'/' + $(this).data('faq'),
                        success: function (data) {

                            let faq = data.allFaqs
                            let assignedFaqs = data.eventFaqs;
                            let accordion = '';
                            let index = 0;

                            $.each(faq, function(key,val){

                                accordion +=

                                `<div class="card">
                                    <div class="card-header" id="accordion_topicc` + index +`" data-toggle="collapse" data-target="#accordion_topic` + index +`" aria-expanded="false" aria-controls="collapseOne">
                                        <h5 class="mb-0">` + key + `</h5>
                                    </div>

                                    <div id="accordion_topic` + index +`" class="collapse" aria-labelledby="accordion_topic` + index +`" data-parent="#accordionTopicMain1">
                                        <div class="card-body">
                                            <div class="table-responsive py-4">
                                                <table class="table align-items-center table-flush"  id="datatable-basic10">
                                                    <thead class="thead-light">
                                                        <tr>
                                                        <th scope="col">{{ __('Title') }}</th>
                                                        <th scope="col">{{ __('Operations') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="faq-body" class="faq-order">`;

                                                    $.each(val, function(key,val1){
                                                        accordion +=
                                                            `<tr id="faq-` + val1['id'] +`" data-id="` + val1['id'] +`" class="faq-list">
                                                                <td>`+ val1['question']  +`</td>`;

                                                            if(assignedFaqs.indexOf(val1.id) !== -1){
                                                                accordion += `<td>
                                                                            <button class="btn btn-primary unsing" data-faq = '` + val1.id +`' type="button">unsign</button>
                                                                             </td>`
                                                            }else{
                                                                accordion += ` <td><button class="btn btn-primary assing" data-faq = '` + val1.id +`' type="button">assign</button></td>`
                                                            }

                                                    });
                                                accordion +=`
                                                    </tr></tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>

                                </div>`

                                index += 1;

                            });

                            $("#accordionTopicMain1").empty();
                            $("#accordionTopicMain1").append(accordion)
                            faqOrder();

                            $(".assing").unbind("click");
                            $("#success-message p").html(data.success);
                            $("#success-message").show();
                        }
                    })
                });
                $(document).on('click', '.unsing',function(e) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'get',
                        url: '/admin/faqs/unsign-event/' + "{{$event->id}}" +'/' + $(this).data('faq'),
                        success: function (data) {
                            let faq = data.allFaqs
                            let assignedFaqs = data.eventFaqs;

                            let accordion = '';
                            let index = 0;

                            $.each(faq, function(key,val){

                                accordion +=

                                `<div class="card">
                                    <div class="card-header" id="accordion_topicc` + index +`" data-toggle="collapse" data-target="#accordion_topic` + index +`" aria-expanded="false" aria-controls="collapseOne">
                                        <h5 class="mb-0">` + key + `</h5>
                                    </div>

                                    <div id="accordion_topic` + index +`" class="collapse" aria-labelledby="accordion_topic` + index +`" data-parent="#accordionTopicMain1">
                                        <div class="card-body">
                                            <div class="table-responsive py-4">
                                                <table class="table align-items-center table-flush"  id="datatable-basic11">
                                                    <thead class="thead-light">
                                                        <tr>
                                                        <th scope="col">{{ __('Title') }}</th>
                                                        <th scope="col">{{ __('Operations') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="faq-body" class="faq-order">`;

                                                    $.each(val, function(key,val1){
                                                        accordion +=
                                                            `<tr id="faq-` + val1['id'] +`" data-id="` + val1['id'] +`" class="faq-list">
                                                                <td>`+ val1['question']  +`</td>`;

                                                            if(assignedFaqs.indexOf(val1.id) !== -1){
                                                                accordion += `<td>
                                                                            <button class="btn btn-primary unsing" data-faq = '` + val1.id +`' type="button">unsign</button>
                                                                             </td>`
                                                            }else{
                                                                accordion += ` <td><button class="btn btn-primary assing" data-faq = '` + val1.id +`' type="button">assign</button></td>`
                                                            }

                                                    });
                                                accordion +=`
                                                    </tr></tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>

                                </div>`

                                index += 1;

                            });

                            $("#accordionTopicMain1").empty();
                            $("#accordionTopicMain1").append(accordion)
                            faqOrder();

                            $(".unsing").unbind("click");
                            $("#success-message p").html(data.success);
                            $("#success-message").show();
                        }
                    })
                });
    </script>


<script src="{{ asset('js/sortable/Sortable.js') }}"></script>

<script>

    $(document).ready(function(){
        faqOrder();
    })

    function faqOrder(){

        var el;


        $( ".faq-order" ).each(function( index ) {

            el = document.getElementsByClassName('faq-order')[index];
            new Sortable(el, {
                group: "words",
                handle: ".my-handle",
                draggable: ".item",
                ghostClass: "sortable-ghost",

            });

            new Sortable(el, {
                // Element dragging ended
                onEnd: function ( /**Event*/ evt) {
                    orderFaqs()
                },
            });

        });

        el = document.getElementById('accordionTopicMain1');

        new Sortable(el, {
           group: "words",
           handle: ".my-handle",
           draggable: ".item",
           ghostClass: "sortable-ghost",

        });

        new Sortable(el, {

            // Element dragging ended
            onEnd: function ( /**Event*/ evt) {

                orderFaqs()


            },
        });
    }


   function orderFaqs(){
      let faqs={}

      $( ".faq-list" ).each(function( index ) {
        faqs[$(this).data('id')] = index
      });


        $.ajax({
         type: 'POST',
         headers: {
         'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
         },
         Accept: 'application/json',
         url: "{{ route ('sort-faqs', $event->id) }}",
         data:{'faqs':faqs},
         success: function(data) {


         }
        });
   }

   $(document).ready( function () {
      $('.faq-table').dataTable( {
          "ordering": false,
          "paging": false
      });
   });

</script>

@endpush

