$('.myfile').on('change', function () {
    var input = this;
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('.Front_img').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
    $('.myfile').attr('is_file', 1);
});

$('.deleteButton').on('click', function () {
    $('.Front_img').attr('src', defaultImagePath);
    $('.myfile').val('');
    $('.myfile').removeAttr('is_file');
});

const togglePassword = document
    .querySelector('#togglePassword1');
const password = document.querySelector('#newPassword');
togglePassword.addEventListener('click', () => {
    // Toggle the type attribute using
    // getAttribure() method
    const type = password
        .getAttribute('type') === 'password' ?
        'text' : 'password';
    password.setAttribute('type', type);
    if (password.getAttribute('type') === 'password') {
        // Toggle the eye and bi-eye icon
        togglePassword.setAttribute('icon', 'fa:eye-slash');
    } else if (password.getAttribute('type') === 'text') {
        // Toggle the eye and bi-eye icon
        togglePassword.setAttribute('icon', 'fa:eye');
    }
});

const togglePassword2 = document
    .querySelector('#togglePassword');
const password2 = document.querySelector('#currentPassword');
togglePassword2.addEventListener('click', () => {
    // Toggle the type attribute using
    // getAttribure() method
    const type2 = password2
        .getAttribute('type') === 'password' ?
        'text' : 'password';
    password2.setAttribute('type', type2);
    if (password2.getAttribute('type') === 'password') {
        // Toggle the eye and bi-eye icon
        togglePassword2.setAttribute('icon', 'fa:eye-slash');
    } else if (password2.getAttribute('type') === 'text') {
        // Toggle the eye and bi-eye icon
        togglePassword2.setAttribute('icon', 'fa:eye');
    }
});

$('.submit').on('click', function () {
    if ($('#profile').attr('is_file') == 1) {
        $(this).closest('form').append(`<input type="hidden" name="is_file" value="1"/>`);
    }
    $(this).closest('form').submit();
});
