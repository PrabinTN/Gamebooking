<?php
/**
 * Template Name: Booking Page
 */
get_header();

// Retrieve session data
$booking_date = isset($_SESSION['booking_date']) ? $_SESSION['booking_date'] : '';
$adults = isset($_SESSION['adults']) ? $_SESSION['adults'] : 0;
$kids = isset($_SESSION['kids']) ? $_SESSION['kids'] : 0;

function generate_time_slots($start_time = "00:00", $end_time = "23:59", $slot_duration = 80, $interval = 10) {
    $slots = [];
    $start = strtotime($start_time);
    $end = strtotime($end_time);
    
    while ($start + ($slot_duration * 60) <= $end) {
        $slot_end = $start + ($slot_duration * 60);
        $slots[] = date("H:i", $start) . " - " . date("H:i", $slot_end);
        $start += $interval * 60;
    }
    
    return $slots;
}

$time_slots = generate_time_slots();
?>

<div class="container booking-page">
    <h3 class="text-center booking-title">Booking Details</h3>

    <table class="booking-table" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>Game Title</th>
                <th>Game Mode</th>
                <th>Price (Adults)</th>
                <th>Price (Kids)</th>
                <th>Price (Standard)</th>
                <th>Time Slot</th>
            </tr>
        </thead>
        <tbody id="bookingTableBody">
            <tr><td colspan="6">Loading...</td></tr>
        </tbody>
    </table>

    <div class="summary-section">
        <h4>Summary</h4>
        <p><strong>Date of Booking:</strong> <?php echo esc_html($booking_date); ?></p>
        <p><strong>Adults:</strong> <?php echo esc_html($adults); ?></p>
        <p><strong>Kids:</strong> <?php echo esc_html($kids); ?></p>
        <p><strong>Total Price:</strong> <span id="totalPrice">$0.00</span></p>
    </div>

    <div class="payment-section text-center">
        <form id="paymentForm">
            <button type="button" id="checkout-button" class="btn btn-primary">Proceed to Payment</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var selectedGames = JSON.parse(sessionStorage.getItem('selected_games')) || [];
    var selectedModes = JSON.parse(sessionStorage.getItem('selected_modes')) || {};
    var selectedPrices = JSON.parse(sessionStorage.getItem('selected_prices')) || {};
    var tableBody = document.getElementById('bookingTableBody');
    var totalPrice = 0;
    var adultsCount = <?php echo esc_js($adults); ?>;
    var kidsCount = <?php echo esc_js($kids); ?>;
    var timeSlots = <?php echo json_encode($time_slots); ?>;
    var selectedTimeSlots = []; // Tracks selected time slots

    if (selectedGames.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="6">No games selected.</td></tr>';
    } else {
        tableBody.innerHTML = '';

        selectedGames.forEach(gameId => {
            let gameMode = selectedModes[gameId] || 'Not Selected';
            let priceData = selectedPrices[gameId] || { adult: "0", child: "0", standard: "0" };
            let adultPrice = parseFloat(priceData.adult) || 0;
            let kidPrice = parseFloat(priceData.child) || 0;
            let standardPrice = parseFloat(priceData.standard) || 0;
            let modeMultiplier = gameMode.includes("2 Games") || gameMode.includes("60 Minutes") ? 2 : 
                                 gameMode.includes("3 Games") || gameMode.includes("90 Minutes") ? 3 : 
                                 gameMode.includes("4 Games") || gameMode.includes("120 Minutes") ? 4 : 1;

            fetch(`<?php echo site_url('/wp-json/wp/v2/games/'); ?>${gameId}`)
                .then(response => response.json())
                .then(gameData => {
                    let gameTitle = gameData.title.rendered;
                    let totalGamePrice = standardPrice > 0 
                        ? standardPrice * (adultsCount + kidsCount) * modeMultiplier 
                        : (adultPrice * adultsCount + kidPrice * kidsCount) * modeMultiplier;

                    totalPrice += totalGamePrice;

                    let timeSlotOptions = timeSlots.map(slot => {
                        const disabled = selectedTimeSlots.includes(slot) ? 'disabled' : '';
                        return `<option value='${slot}' ${disabled}>${slot}</option>`;
                    }).join('');

                    let timeSlotSelect = `<select class="time-slot-select">
                        <option value="">Select Time Slot</option>
                        ${timeSlotOptions}
                    </select>`;

                    let row = `<tr>
                        <td>${gameTitle}</td>
                        <td>${gameMode}</td>
                        <td>$${adultPrice.toFixed(2)}</td>
                        <td>$${kidPrice.toFixed(2)}</td>
                        <td>$${standardPrice.toFixed(2)}</td>
                        <td>${timeSlotSelect}</td>
                    </tr>`;

                    tableBody.innerHTML += row;

                    document.getElementById('totalPrice').innerText = '$' + totalPrice.toFixed(2);

                    // Add change event to manage selected time slots
                    document.querySelectorAll('.time-slot-select').forEach(select => {
                        select.addEventListener('change', function() {
                            const selectedValue = this.value;

                            // Remove previously selected slot from the tracking array
                            const prevSelectedSlot = this.getAttribute('data-selected-slot');
                            if (prevSelectedSlot) {
                                selectedTimeSlots = selectedTimeSlots.filter(slot => slot !== prevSelectedSlot);
                            }

                            // Add newly selected slot
                            if (selectedValue) {
                                selectedTimeSlots.push(selectedValue);
                                this.setAttribute('data-selected-slot', selectedValue);
                            } else {
                                this.removeAttribute('data-selected-slot');
                            }

                            // Refresh dropdown options to disable selected slots
                            document.querySelectorAll('.time-slot-select').forEach(select => {
                                select.querySelectorAll('option').forEach(option => {
                                    if (selectedTimeSlots.includes(option.value)) {
                                        option.disabled = true;
                                    } else {
                                        option.disabled = false;
                                    }
                                });

                                // Ensure the already selected option remains active
                                const selectedOption = select.getAttribute('data-selected-slot');
                                if (selectedOption) {
                                    select.querySelector(`option[value='${selectedOption}']`).disabled = false;
                                }
                            });
                        });
                    });
                })
                .catch(error => console.error('Error fetching game data:', error));
        });
    }
});

</script>

<script src="https://js.stripe.com/v3/"></script>
<script>
document.getElementById("checkout-button").addEventListener("click", function() {
   fetch("<?php echo site_url('/wp-json/booking/v1/create-payment-session'); ?>", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
        date: "<?php echo esc_html($booking_date); ?>",
        selected_products: JSON.parse(sessionStorage.getItem('selected_games')) || [],
        zone_times: JSON.parse(sessionStorage.getItem('selected_times')) || {}
    })
})
    .then(response => response.json())
    .then(session => {
        if(session.id) {
            var stripe = Stripe("<?php echo STRIPE_PUBLISHABLE_KEY; ?>");
            stripe.redirectToCheckout({ sessionId: session.id });
        } else {
            alert("Error creating Stripe session. " + (session.error || "Please try again."));
        }
    })
    .catch(error => {
        console.error('Stripe session error:', error);
        alert("Error creating Stripe session. Please try again.");
    });
});
</script>

<?php get_footer(); ?>
