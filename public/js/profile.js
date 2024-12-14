$(document).ready(function () {
    $.ajax({
        url: '/api/profile',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                const data = response.data;
                $('#username').text(data.username);
                $('#name').text(data.name);
                $('#bio').text(data.bio || 'No bio available.');
                $('#profileImage').attr('src', data.profile_image || '/default-profile.png');
                $('#phoneNumber').text(data.phone_number || 'No phone number provided.');
                $('#address').text(data.address || 'No address provided.');
            } else {
                console.error('Error:', response.message);
                alert('Failed to load profile data.');
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', status, error);
            alert('An error occurred while fetching profile data.');
        },
    });
});
