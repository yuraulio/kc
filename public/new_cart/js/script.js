$(document).ready(function() {
    $('.radio-group .custom-radio .custom-control-input').click(function() {
        $('.radio-group .custom-radio p').removeClass('active');
        $(this).next('p').addClass('active');
    });

    var cloneCount = 2;
    $(".add-participant").click(function() {
      
        /*cloneCount = cloneCount + 1;
        var clone = $('#clone-box')
            .clone()
            .attr('id', 'clone-box-' + cloneCount)
            .insertAfter($('[id^=clone-box]:last'));

        clone.find('h2').text(`Participant ${cloneCount}`);
        clone.find('.custom-checkbox input[type="checkbox"]').attr('id', 'customCheck-' + cloneCount);
        clone.find('.custom-control-label').attr('for', 'customCheck-' + cloneCount);
        $('.participant-number').css("display", "block");*/
    });

    $('form').on('click', '.remove-participant', function() {
        $('.remove-participant').closest('form').find('.form-wrp').not(':first').last().remove();
    });


    $('.payment-method .custom-control-input').click(function() {

      $('.payment-method').prop('checked', false);
      $(this).parent().find('.payment-method').prop('checked', true);

      $('.card-info1').addClass('payment-method-hidden');
      $(this).parent().find('.card-info1').removeClass('payment-method-hidden');

  });


});


document.body.addEventListener('mousedown', function() {
  document.body.classList.add('using-mouse');
});

// Re-enable focus styling when Tab is pressed
document.body.addEventListener('keydown', function(event) {
  if (event.keyCode === 9) {
    document.body.classList.remove('using-mouse');
  }
});