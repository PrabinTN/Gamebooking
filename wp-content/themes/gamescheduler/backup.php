<?php
/**
 * Template Name: Booking Page
 */
get_header();


// Start the session if not already started
if (!session_id()) {
    session_start();
}

// Retrieve session data
$booking_date = isset($_SESSION['booking_date']) ? $_SESSION['booking_date'] : '';
$adults = isset($_SESSION['adults']) ? $_SESSION['adults'] : 0;
$kids = isset($_SESSION['kids']) ? $_SESSION['kids'] : 0;
$selected_time = isset($_SESSION['selected_time']) ? $_SESSION['selected_time'] : '';

// Initialize empty PHP arrays for games and modes (will be filled by JavaScript)
$selected_games = [];
$selected_game_modes = [];
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
        <form action="your_payment_url" method="POST">
            <button type="submit" class="btn btn-primary">Proceed to Payment</button>
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

            totalPrice += adultPrice + kidPrice;

            tableBody.innerHTML += `<tr>
                <td>Game ${gameId}</td>
                <td>${gameMode}</td>
                <td>$${adultPrice.toFixed(2)}</td>
                <td>$${kidPrice.toFixed(2)}</td>
                <td>$${standardPrice.toFixed(2)}</td>
                <td>
                    <select class="time-slot-select">
                        <option value="">Select Time Slot</option>
                        <option value="14:00 - 15:30">14:00 - 15:30</option>
                    </select>
                </td>
            </tr>`;
        });

        document.getElementById('totalPrice').innerText = '$' + totalPrice.toFixed(2);
    }
});
</script>

<?php get_footer(); ?>








<?php
/**
 * Template Name: Booking Page
 */
get_header();


// Start the session if not already started
if (!session_id()) {
    session_start();
}

// Retrieve session data
$booking_date = isset($_SESSION['booking_date']) ? $_SESSION['booking_date'] : '';
$adults = isset($_SESSION['adults']) ? $_SESSION['adults'] : 0;
$kids = isset($_SESSION['kids']) ? $_SESSION['kids'] : 0;
$selected_time = isset($_SESSION['selected_time']) ? $_SESSION['selected_time'] : '';

// Initialize empty PHP arrays for games and modes (will be filled by JavaScript)
$selected_games = [];
$selected_game_modes = [];
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
        <form action="your_payment_url" method="POST">
            <button type="submit" class="btn btn-primary">Proceed to Payment</button>
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

            totalPrice += adultPrice + kidPrice;

            tableBody.innerHTML += `<tr>
                <td>Game ${gameId}</td>
                <td>${gameMode}</td>
                <td>$${adultPrice.toFixed(2)}</td>
                <td>$${kidPrice.toFixed(2)}</td>
                <td>$${standardPrice.toFixed(2)}</td>
                <td>
                    <select class="time-slot-select">
                        <option value="">Select Time Slot</option>
                        <option value="14:00 - 15:30">14:00 - 15:30</option>
                    </select>
                </td>
            </tr>`;
        });

        document.getElementById('totalPrice').innerText = '$' + totalPrice.toFixed(2);
    }
});
</script>

<?php get_footer(); ?>



















