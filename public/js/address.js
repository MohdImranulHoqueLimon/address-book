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
            isExist: true,
            required: true,
            email: true,
        }
    }
});

$.validator.addMethod("isExist", function (value, element) {
    var result = true;
    $.ajax({
        url: '/is-email-exist',
        type: 'POST',
        data: {
            email: value
        },
        async: false,
        timeout: 3000,
        success: function(response) {
            if(response['result'] == true){
                result = false;
            } else {
                result = true;
            }
        }
    });
    return result;
}, 'This email already exist.');