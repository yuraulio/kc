
<div class="row align-items-center">
    <div class="col-8">
        <h3 class="mb-0">{{ __('Faqs') }}</h3>
        <p class="text-sm mb-0">
                {{ __('This is an example of Faq management.') }}
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

<div class="table-responsive py-4">
    <table class="table align-items-center table-flush faq-table"  id="datatable-basic">
        <thead class="thead-light">
            <tr>
                <th scope="col">{{ __('Title') }}</th>
                <th scope="col">{{ __('Category') }}</th>
                <th scope="col">{{ __('Operations') }}</th>
                
            </tr>
        </thead>
        <tbody id="faq-body" class="faq-order">
        @if($event->category->first())
    
            @foreach ($event->category->first()->faqs as $faq)
                <tr id="faq-{{$faq->id}}" data-id="{{$faq->id}}" class="faq-list">
                    <td>{{ $faq->title }}</td>
                    <td>{{ $faq->category->first()->name }}</td>
                    

                    <td> 
                        @if(!in_array($faq->id,$eventFaqs)) 
                            <button class="btn btn-primary assing" data-faq = '{{$faq->id}}' type="button">Assign</button>
                        @else
                            <button class="btn btn-primary unsing" data-faq = '{{$faq->id}}' type="button">Unsign</button>
                        @endif
                    </td>
                   
                       
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
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
                            $('#faq-body tr').remove();

                            
                            $.each(faq, function(key,val){
                            
                                let newFaq =
                                `<tr id="faq-`+val.id+`">` +
                                `<td id="title-` + val.id +`">` + val.title + `</td>` +
                            
                                `<td id="category-` + val.id +`">` + val.category[0]['name'] + `</td>`
                                
                                if(assignedFaqs.indexOf(val.id) !== -1){
                                
                                    newFaq +=
                                    
                                    ` <td><button class="btn btn-primary unsing" data-faq = '` + val.id +`' type="button">unsign</button></td>` +
                                    `</tr>`;
                                }else{
                                    newFaq +=
                            
                                    ` <td><button class="btn btn-primary assing" data-faq = '` + val.id +`' type="button">assign</button></td>` +
                                    `</tr>`;
                                }
                                
                                
                                $("#faq-body").append(newFaq);
                            })

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

                            
                            $('#faq-body tr').remove();

                            $.each(faq, function(key,val){
                            
                                let newFaq =
                                `<tr id="faq-`+val.id+`">` +
                                `<td id="title-` + val.id +`">` + val.title + `</td>` +
                            
                                `<td id="category-` + val.id +`">` + val.category[0]['name'] + `</td>`;
                                
                                if(assignedFaqs.indexOf(val.id) !== -1){
                                    newFaq +=
                            
                                    ` <td><button class="btn btn-primary unsing" data-faq = '` + val.id +`' type="button">unsign</button></td>` +
                                    `
                                        
                                    </tr>`;
                                }else{
                                    newFaq +=
                            
                                    ` <td><button class="btn btn-primary assing" data-faq = '` + val.id +`' type="button">assign</button></td>` +
                                    `

                                    </tr>`;
                                }
                                
                                
                                $("#faq-body").append(newFaq);
                            })

                            $(".unsing").unbind("click");
                            $("#success-message p").html(data.success);
                            $("#success-message").show();
                        }
                    })
                });
    </script>


<script src="{{ asset('js/sortable/Sortable.js') }}"></script>

<script>

   (function( $ ){
      
      var el = document.getElementsByClassName('faq-order')[0];
         
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

   })( jQuery );

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

