$(document).ready(function() {
    $('#togglePassword').on('click', function() {
        const $password = $('#password');
        const $icon = $('#eyeIcon');

        const type = $password.attr('type') === 'password' ? 'text' : 'password';
        $password.attr('type', type);

        $icon.toggleClass('fa-eye fa-eye-slash');
    });
});
