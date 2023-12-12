<div class='modal-header text-center'>
<h4 class='modal-title w-100 font-weight-bold'><?= $title ?></h4>
    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
        <span aria-hidden='true'>&times;</span>
    </button>
</div>
<div class='modal-body modal-content mx-3'">
    <table class="table table-hover" data-plugin="selectable" data-row-selectable="true">
        <thead>
          <tr>
            <th class="hidden-sm-down">Name</th>
            <th class="hidden-sm-down">Cost</th>
            <th class="hidden-sm-down">Charge period</th>
            <th class="hidden-sm-down">Trial</th>
            <th class="hidden-sm-down">Action</th>
          </tr>
        </thead>
        <tbody class="modal_table">
        <?php //dd($plans) ?>

        @if($plans !=0)

        @foreach($plans as $key => $plan)

        <tr id="<?= $plan['id'] ?>">
            <td class="title"><?= $plan['title'] ?></td>
            <td class="cost"><?= $plan['cost'] ?></td>
            <td class="description"><?= $plan['period'] ?></td>
            <td class="description"><?= $plan['trial_days'] ?></td>
            <td>
                <!-- <a href="javascript:void(0)" data-plan-id="<?= $plan['id'] ?>" id="edit-plan" class="btn btn-primary btn-sm">Edit</a> -->
                <a href="javascript:void(0)" data-plan-id="<?= $plan['id'] ?>" class="btn btn-danger btn-sm remove-plan">Remove</a>
            </td>
        </tr>
        @endforeach

        @endif


        </tbody>
    </table>
    <h3>Add Plan</h4>
    <div class="dropdown plan show">
  <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    select
  </a>

  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
    <a class="dropdown-item plan" id="select1" href="javascript:void(0)">1 plan</a>
    <a class="dropdown-item plan" id="select2" href="javascript:void(0)">2 plan</a>
  </div>
</div>
    <form class="eventAddPlan">
        <div class="hidden">
            <div class="form-group">
                <label for="recipient-name" class="col-form-label">Name:</label>
                <span class="error-hint 1 name1">This field is required</span>
                <input type="text" class="form-control" id="name1">
            </div>
            <div class="form-group">
                <label for="message-text" class="col-form-label">Cost:</label>
                <span class="error-hint 1 cost_req1">This field is required</span>
                <span class="error-hint 2 cost_num1">This field must be a number</span>
                <input type="text" class="form-control" id="cost1"></input>
            </div>

            <div class="form-group">
                <label for="message-text" class="col-form-label">Trial days:</label>
                <span class="error-hint 1 trial_req1">This field is required</span>
                <span class="error-hint 2 trial_num1">This field must be a number</span>
                <input type="text" class="form-control" id="trial1" value="0"></input>
            </div>


            <div class="form-group">
                <label for="message-text" class="col-form-label period">Charge Period:</label>
                <input type="radio" id="period1" name="period1" value="month" checked>
                <label style="color:black;" for="male">month</label>
                <input type="radio" id="period1" name="period1" value="year">
                <label style="color:black;" for="female">Year</label><br>
                <!-- <label style="color:black;" class="radio-inline period"><input type="hidden" name="period1" id="period1" value="month">Month</label> -->
            </div>
        </div>

        <div class="hidden">
            <div class="form-group">
                <label for="recipient-name" class="col-form-label">Name:</label>
                <span class="error-hint 1 name2">This field is required</span>
                <input type="text" class="form-control" id="name2">
            </div>
            <div class="form-group">
                <label for="message-text" class="col-form-label">Cost:</label>
                <span class="error-hint 1 cost_req2">This field is required</span>
                <span class="error-hint 2 cost_num2">This field must be a number</span>
                <input type="text" class="form-control" id="cost2"></input>
            </div>

            <div class="form-group">
                <label for="message-text" class="col-form-label">Trial days:</label>
                <span class="error-hint 1 trial_req1">This field is required</span>
                <span class="error-hint 2 trial_num1">This field must be a number</span>
                <input type="text" class="form-control" id="trial2" value="0"></input>
            </div>

            <div class="form-group">
                <label for="message-text" class="col-form-label period">Charge Period:</label>
                <input type="radio" id="period2" name="period2" value="month">
                <label style="color:black;" for="male">month</label>
                <input type="radio" id="period2" name="period2" value="year" checked>
                <label style="color:black;" for="female">Year</label><br>
            </div>
        </div>

    </form>
</div>



<div class="modal-footer">
    <div class="text-right">
    <a href="javascript:void(0)" data-event-id="<?= $event_id ?>" id="add-plan" class="btn btn-primary">Add Plan</a>
    <a href="javascript:void(0)" data-plan-id="" id="save-plan" class="btn btn-primary hidden">Save</a>
</div>

  <!-- <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button> -->
</div>
<script>

// $('#mainModal').on('show.bs.modal', function () {
//     $('#mainModal').find('form')[0].reset();
// 	alert(this)
// })
// $('#viewPlan').click(function (e) {
//     console.log(this)
// })

    $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
    });

    $('#select1').click(function() {
        const elem1 = $('form').find('div')[0]
        $(elem1).removeClass('hidden');
        const elem2 = $('form').find('div')[5]
        $(elem2).addClass('hidden');
    });

    $('#select2').click(function() {
        const elem1 = $('form').find('div')[5]
        const elem2 = $('form').find('div')[0]
        $(elem1).removeClass('hidden');
        $(elem2).removeClass('hidden');
    });

    function validate(num)
    {
        if(num == 1){
            if(!$('#name1').val()){
            $('.error-hint.1.name1').addClass('error')
            return false
            }
            if(!$('#cost1').val()){
                $('.error-hint.1.cost_req1').addClass('error')
                return false
            }
            if(isNaN($('#cost1').val())){
                $('.error-hint.2.cost_num1').addClass('error')
                return false
            }
            if(!$('#trial1').val()){
                $('.error-hint.1.trial_req1').addClass('error')
                return false
            }
            if(isNaN($('#trial1').val())){
                $('.error-hint.2.trial_num1').addClass('error')
                return false
            }
            $('.error-hint').removeClass('error')
            return true
        }else{
            if(!$('#name1').val()){
            $('.error-hint.1.name1').addClass('error')
            return false
            }
            if(!$('#cost1').val()){
                $('.error-hint.1.cost_req1').addClass('error')
                return false
            }
            if(isNaN($('#cost1').val())){
                $('.error-hint.2.cost_num1').addClass('error')
                return false
            }
            if(!$('#trial1').val()){
                $('.error-hint.1.trial_req1').addClass('error')
                return false
            }
            if(isNaN($('#trial1').val())){
                $('.error-hint.2.trial_num1').addClass('error')
                return false
            }
            if(!$('#name2').val()){
            $('.error-hint.1.name2').addClass('error')
            return false
            }
            if(!$('#cost2').val()){
                $('.error-hint.1.cost_req2').addClass('error')
                return false
            }
            if(isNaN($('#cost2').val())){
                $('.error-hint.2.cost_num2').addClass('error')
                return false
            }
            if(!$('#trial2').val()){
                $('.error-hint.1.trial_req1').addClass('error')
                return false
            }
            if(isNaN($('#trial2').val())){
                $('.error-hint.2.trial_num1').addClass('error')
                return false
            }
            $('.error-hint').removeClass('error')
            return true
        }

    }

    $( "#edit-plan" ).click(function(e) {
        let plan_id = $(this).data('planId')
        let name = $('#'+plan_id).find('.title').text()
        let cost = parseFloat($('#'+plan_id).find('.cost').text())
        let period = $("input[type='radio']:checked").val();
        //alert(period)

        $('#save-plan').attr('data-plan-id', plan_id)
        $('#add-plan').addClass('hidden')
        $('#save-plan').removeClass('hidden')

        $('#name').val(name)
        $('#cost').val(cost)
        //$('#period').val(description)
        $('#period').prop('checked', true);

    })

    $( "#save-plan" ).click(function(e) {
        const plan_id = $('#save-plan').data('planId')
        const name = $('#name').val()
        const cost = $('#cost').val()
        const period = $('#period').val()
        if(validate()){
            $.ajax({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            type: 'post',
            url: '/admin1/plan/edit',
            data:{'plan_id':plan_id,'name':name, 'cost':cost, 'period':period},
            success: function(data) {
                    if(data){

                        $('#'+data['data']['plan_id']).find('.title').text(data['data']['name'])
                        $('#'+data['data']['plan_id']).find('.cost').text(data['data']['cost'])
                        $('#'+data['data']['plan_id']).find('.description').text(data['data']['period'])


                        $('#name').val('')
                        $('#cost').val('')
                        $('#period').val('')
                        $('#add-plan').removeClass('hidden')
                        $('#save-plan').addClass('hidden')

                    }else{
                        console.log("fail from edit")
                    }
                    //playVi = true;

            }
            });
        }


    })


    $( "#add-plan" ).click(function(e) {
        const event_id = $(this).data('eventId')
        let name2 = ''
        let cost2 = 0
        let period2 = ''
        let trial2 = 0

        let elem2 = $('form').find('div')[5]
        if($(elem2).hasClass('hidden')){
            var isValid = validate(1);
        }else{
            var isValid = validate(2);
        }

        //var isValid = validate()
        if(isValid){
            $.ajax({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            type: 'post',
            url: '/admin1/plan/add',
            data:{'event_id':event_id,'name1':$('#name1').val(), 'cost1':$('#cost1').val(), 'period1':$("#period1:checked").val(),'trial1':$('#trial1').val(),'name2':$('#name2').val(), 'cost2':$('#cost2').val(),'trial2':$('#trial2').val(), 'period2':$("#period2:checked").val()},
            success: function(data) {
                    if(data){
                        // APPEND IN ROW
                        if(data['data']['plan_id2'])
                        {
                            html = `<tr id="${data['data']['plan_id1']}">
                                    <td class="title">${data['data']['name1']}</td>
                                    <td class="cost">${data['data']['cost1']}</td>
                                    <td class="description">${data['data']['period1']}</td>
                                    <td class="description">${data['data']['trial1']}</td>
                                    <td>
                                        <a href="javascript:void(0)" data-plan-id="${data['data']['plan_id1']}" class="btn btn-danger btn-sm remove-plan">Remove</a>
                                    </td>
                                </tr>`
                                $('.modal_table').append(html)
                                html = `<tr id="${data['data']['plan_id2']}">
                                    <td class="title">${data['data']['name2']}</td>
                                    <td class="cost">${data['data']['cost2']}</td>
                                    <td class="description">${data['data']['period2']}</td>
                                    <td class="description">${data['data']['trial2']}</td>
                                    <td>
                                        <a href="javascript:void(0)" data-plan-id="${data['data']['plan_id2']}" class="btn btn-danger btn-sm remove-plan">Remove</a>
                                    </td>
                                </tr>`
                                $('.modal_table').append(html)
                        }else{
                            html = `<tr id="${data['data']['plan_id1']}">
                                    <td class="title">${data['data']['name1']}</td>
                                    <td class="cost">${data['data']['cost1']}</td>
                                    <td class="description">${data['data']['period1']}</td>
                                    <td class="description">${data['data']['trial1']}</td>
                                    <td>
                                        <a href="javascript:void(0)" data-plan-id="${data['data']['plan_id1']}" class="btn btn-danger btn-sm remove-plan">Remove</a>
                                    </td>
                                </tr>`
                                $('.modal_table').append(html)
                        }


                            $('#name1').val('')
                            $('#cost1').val('')
                            $('#trial1').val('')
                            $('#name2').val('')
                            $('#cost2').val('')
                            $('#trial2').val('')
                    }else{
                    }
            }
            });
        }



    });

    $('.table').on('click',".remove-plan",function(e) {
        const id = $(this).data('planId')
        $.ajax({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
          type: 'post',
          url: '/admin1/plan/remove',
          data:{'id':id},
          success: function(data) {
                if(data){
					$('#'+data['data']).remove();
                }else{
                    console.log('fail remove')
                }
                //playVi = true;

          }
        });
    });

</script>
