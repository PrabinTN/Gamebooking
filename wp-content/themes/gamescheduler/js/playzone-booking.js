jQuery(document).ready(function ($) {
    $('#confirmGuest').on('click', function () {
        let bookingDate = $('#bookingDate').val();
        let adults = $('#numAdults').val();
        let kids = $('#numKids').val();
        let selectedTime = $('#bookingTime').val();

        if (!bookingDate || !adults || !kids || !selectedTime) {
            alert('Please fill in all details.');
            return;
        }

        $.ajax({
            url: playzone_api.ajax_url + 'guest-selection/',  // Make sure this URL matches the route in your custom REST API
            method: 'POST',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', playzone_api.nonce);
            },
            contentType: 'application/json',
            data: JSON.stringify({
                date: bookingDate,
                adults: adults,
                kids: kids,
                time: selectedTime
            }),
            success: function (response) {
                // Redirect to activities page after data is saved
                window.location.href = '/PlayZoneScheduler/activities';  // Corrected URL
            },
            error: function () {
                alert('Error saving guest details.');
            }
        });
    });
});



jQuery(document).ready(function ($) {
    let selectedGames = [];

    $('.game-checkbox').on('change', function () {
        let gameId = $(this).val();
        if ($(this).is(':checked')) {
            selectedGames.push(gameId);
            $(this).next('.added-text').text('Added');
        } else {
            selectedGames = selectedGames.filter(id => id !== gameId);
            $(this).next('.added-text').text('');
        }
    });

    $('#nextToBooking').on('click', function () {
        if (selectedGames.length === 0) {
            alert('Please select at least one game.');
            return;
        }

        console.log("Selected games: ", selectedGames); // Check the selected games in the console

        $.ajax({
            url: playzone_api.ajax_url + 'game-selection/', 
            method: 'POST',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', playzone_api.nonce);
            },
            contentType: 'application/json',
            data: JSON.stringify({ selected_games: selectedGames }),
            success: function (response) {
                console.log("Response: ", response); // Log the response
                window.location.href = 'http://localhost/PlayZoneScheduler/booking'; 
            },
            error: function () {
                alert('Error saving game selection.');
            }
        });
    });
});




jQuery(document).ready(function ($) {
    let selectedTimes = {};

    $('.time-slot').on('change', function () {
        let gameId = $(this).data('game-id');
        let timeSlot = $(this).val();

        if (selectedTimes[gameId] && selectedTimes[gameId] !== timeSlot) {
            alert('Only one time slot can be selected per game.');
            $(this).val(selectedTimes[gameId]); // Reset dropdown
            return;
        }

        selectedTimes[gameId] = timeSlot;
    });

    $('#confirmBooking').on('click', function () {
        if (Object.keys(selectedTimes).length === 0) {
            alert('Please select time slots for your games.');
            return;
        }

        $.ajax({
            url: playzone_api.ajax_url + 'validate-booking/',
            method: 'POST',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', playzone_api.nonce);
            },
            contentType: 'application/json',
            data: JSON.stringify({ selected_times: selectedTimes }),
            success: function (response) {
                generateBookingURL();
            },
            error: function (error) {
                alert('Error validating booking. ' + error.responseJSON.error);
            }
        });
    });

    function generateBookingURL() {
        $.ajax({
            url: playzone_api.ajax_url + 'generate-booking-url/',
            method: 'GET',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', playzone_api.nonce);
            },
            success: function (response) {
                window.location.href = response.booking_url; // Redirect to final booking URL
            },
            error: function () {
                alert('Error generating booking URL.');
            }
        });
    }
});








