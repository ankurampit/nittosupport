$('#show_password').hover(function functionName() {
            //Change the attribute to text
            $('#password').attr('type', 'text');
            $('#show_password .fa').removeClass('fa-eye').addClass('fa-eye-slash');
        }, function () {
            //Change the attribute back to password
            $('#password').attr('type', 'password');
            $('#show_password .fa').removeClass('fa-eye-slash').addClass('fa-eye');
        }
    );
    