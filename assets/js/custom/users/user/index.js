$('.myfile').on('change', function () {
    var input = this;
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('.Front_img').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
});

const togglePassword = document
    .querySelector('#togglePassword');
const password = document.querySelector('#password');
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
