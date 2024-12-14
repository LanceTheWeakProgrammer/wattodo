$(document).ready(function () {

    $('#loginForm').on('submit', function (event) {
        event.preventDefault();

        let username = $('#username').val();
        let password = $('#password').val();

        $.ajax({
            url: '/authenticate',
            type: 'POST',
            data: {username: username, password: password},
            success: function(response) {
                let result = JSON.parse(response);
                if (result.status === 'success') {
                    alert('Login successfully!');
                    window.location.href = '/dashboard'; 
                } else {
                    alert('Login failed: ' + result.message);
                }
            },
            error: function () {
                alert('An error occured');
            }
        });
    });

    $('#registerForm').on('submit', function (event) {
        event.preventDefault();

        $('.text-danger').remove();

        let username = $('#username').val();
        let name = $('#name').val();
        let password = $('#password').val();

        $.ajax({
            url: '/store',
            type: 'POST',
            data: { username: username, name: name, password: password },
            success: function (response) {
                let result = JSON.parse(response);

                if (result.status === 'success') {
                    alert('Registered successfully');
                    window.location.href = '/login';
                } else {
                    if (result.message) {
                        result.message.forEach(error => {
                            if (error.includes('Username')) {
                                $('#username').after(`<span class="text-danger text-sm">${error}</span>`);
                            } else if (error.includes('Name')) {
                                $('#name').after(`<span class="text-danger text-sm">${error}</span>`);
                            } else if (error.includes('Password')) {
                                $('#password').after(`<span class="text-danger text-sm">${error}</span>`);
                            }
                        });
                    }
                }
            },
            error: function () {
                alert('An error occurred during registration.');
            }
        });
    });


    $('#logoutButton').on('click', function (event) {
        $.ajax({
            url: '/logout',
            type: 'POST',
            success: function (response) {
                console.log(response);
                let result = JSON.parse(response);

                if(result.status === 'success') {
                    alert("Logged out successfully");
                    window.location.href = '/login';
                } else {
                    alert('Logout failed' + result.message);
                }
            },
            error: function () {
                alert('An error occured during logout');
            }
        })
    });
})