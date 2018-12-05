$('input[name="birthDay"]').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    locale: {
        format: 'MMM DD, YYYY'
    }
});

$("#addressForm").validate({
    rules: {
        firstName: "required",
        email: {
            required: true,
            email: true,
            //isExist: true,
        }
    }
});

$.validator.addMethod("isExist", function (value, element) {
    var result = true;

    var idVal = $('#id');
    if($('#id').length > 0) {
        idVal = $('#id').val();
    } else {
        idVal = 0;
    }

    $.ajax({
        url: '/is-email-exist',
        type: 'POST',
        data: {
            email: value,
            id: idVal
        },
        async: false,
        timeout: 3200,
        success: function(response) {
            if(response['result'] == true) {
                result = false;
            } else {
                result = true;
            }
        }
    });
    return result;
}, 'This email already exist.');