<?php

require_once get_template_directory() . '/vendor/autoload.php'; 

\Stripe\Stripe::setApiKey('sk_test_51R4a81Inize6Cwja5N24b3fIOTbkP9p14lozmdwA1nyjssNNpyA0jPTe0w7z1BIwfzoWFq3tc8eSPvyaItq6oyXh00PzLcUtEm');

// Start the session if not already started
if (!session_id()) {
    session_start();
}

// Retrieve booking details from the session
$booking_date = $_SESSION['booking_date'] ?? '';
$adults       = $_SESSION['adults'] ?? 0;
$kids         = $_SESSION['kids'] ?? 0;

// Retrieve the total price from the POST data
$totalPrice   = isset($_POST['total_price']) ? floatval($_POST['total_price']) : 0;

// Validate the total price
if ($totalPrice <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid total price.']);
    exit;
}

try {
    // Create a PaymentIntent with Stripe
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount'   => $totalPrice * 100, // Stripe requires amount in cents
        'currency' => 'usd',
        'metadata' => [
            'booking_date' => $booking_date,
            'adults'       => $adults,
            'kids'         => $kids,
        ],
    ]);

    // Return the client secret for the PaymentIntent
    echo json_encode(['clientSecret' => $paymentIntent->client_secret]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
