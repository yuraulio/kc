$(document).on('click', '.registration', function (e) {
  e.preventDefault();

  $('input[name^="firstname["]').each(function (i) {
    let val = $(this).val().split(' ');
    let newVal = '';
    $.each(val, function (index, value) {
      newVal += value.charAt(0).toUpperCase() + value.slice(1).toLowerCase() + ' ';
    });

    $(this).val(newVal.trim());
  });

  $('input[name^="lastname["]').each(function (i) {
    /*let val = $(this).val();
        let newVal = val.charAt(0).toUpperCase() + val.slice(1).toLowerCase();

        $(this).val(newVal);*/

    let val = $(this).val().split(' ');
    let newVal = '';
    $.each(val, function (index, value) {
      newVal += value.charAt(0).toUpperCase() + value.slice(1).toLowerCase() + ' ';
    });

    $(this).val(newVal.trim());
  });

  var checkoutUrl = 'mobile-check';
  var fdata = $('#participant-form').serialize();
  $('.error-mobile').hide();

  $.ajax({
    url: checkoutUrl,
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
    data: fdata,
    success: function (data) {
      //alert('HO');
      //   return;
      $('#participant-form').find('input[type=text]').removeClass('verror');
      if (Number(data.status) === 0) {
        //var html = '<ul>';
        $('#participant-form').valid();
        $.each(data.errors, function (key, row) {
          key = Number(key.split('.')[1]);

          $('#mobile-error' + (key + 1)).html(row);
          $('#mobile-error' + (key + 1)).show();
        });

        //$("#participant-form").valid();
      } else {
        $('#participant-form').submit();
      }
    },
  });
});

$(document).on('click', '.do-checkout-code', function (e) {
  e.preventDefault();

  var checkoutUrl = 'mobile-check';
  var fdata = $('#participant-form').serialize();
  $('.error-mobile').hide();

  $.ajax({
    url: checkoutUrl,
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
    data: fdata,
    success: function (data) {
      //alert('HO');
      //   return;
      $('#participant-form').find('input[type=text]').removeClass('verror');
      if (Number(data.status) === 0) {
        //var html = '<ul>';
        $.each(data.errors, function (key, row) {
          key = Number(key.split('.')[1]);

          $('#mobile-error' + (key + 1)).html(row);
          $('#mobile-error' + (key + 1)).show();
        });
      } else {
        $('#participant-form').submit();
      }
    },
  });
});

$(document).on('click', '.do-checkout-free', function (e) {
  e.preventDefault();

  var checkoutUrl = 'mobile-check';
  var fdata = $('#participant-form').serialize();
  $('.error-mobile').hide();

  $.ajax({
    url: checkoutUrl,
    type: 'get',

    data: fdata,
    success: function (data) {
      //alert('HO');
      //   return;
      $('#participant-form').find('input[type=text]').removeClass('verror');
      if (Number(data.status) === 0) {
        //var html = '<ul>';
        $.each(data.errors, function (key, row) {
          key = Number(key.split('.')[1]);

          $('#mobile-error' + (key + 1)).html(row);
          $('#mobile-error' + (key + 1)).show();
        });
      } else {
        $('#participant-form').submit();
      }
    },
  });
});

$(document).on('click', '.do-checkout-subscription', function (e) {
  e.preventDefault();

  var checkoutUrl = 'mobile-check';
  var fdata = $('#participant-form').serialize();
  $('.error-mobile').hide();

  $.ajax({
    url: checkoutUrl,
    type: 'get',

    data: fdata,
    success: function (data) {
      //alert('HO');
      //   return;
      $('#participant-form').find('input[type=text]').removeClass('verror');
      if (Number(data.status) === 0) {
        //var html = '<ul>';
        $.each(data.errors, function (key, row) {
          key = Number(key.split('.')[1]);

          $('#mobile-error' + (key + 1)).html(row);
          $('#mobile-error' + (key + 1)).show();
        });
      } else {
        $('#participant-form').submit();
      }
    },
  });
});

$(document).on('click', '.do-checkout-waiting', function (e) {
  e.preventDefault();

  var checkoutUrl = 'mobile-check';
  var fdata = $('#participant-form').serialize();
  $('.error-mobile').hide();

  $.ajax({
    url: checkoutUrl,
    type: 'get',

    data: fdata,
    success: function (data) {
      //alert('HO');
      //   return;
      $('#participant-form').find('input[type=text]').removeClass('verror');
      if (Number(data.status) === 0) {
        //var html = '<ul>';
        $.each(data.errors, function (key, row) {
          key = Number(key.split('.')[1]);

          $('#mobile-error' + (key + 1)).html(row);
          $('#mobile-error' + (key + 1)).show();
        });
      } else {
        $('#participant-form').submit();
      }
    },
  });
});
