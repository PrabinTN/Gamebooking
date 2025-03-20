<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once get_template_directory() . '/vendor/autoload.php'; // Load Stripe

require_once('../../../wp-load.php'); // Load WordPress

\Stripe\Stripe::setApiKey('sk_test_your_secret_key'); // Replace with your Secret Key

// Get payment details from the URL
$booking_date = $_GET['date'] ?? '';
$selected_products = $_GET['selected_products'] ?? [];
$zone_times = $_GET['zone_times'] ?? [];

// Debug: Print received parameters
echo "<pre>";
print_r($_GET);
echo "</pre>";

// Ensure selected products are correctly formatted
if (empty($selected_products)) {
    die("Error: No selected products.");
}

$line_items = [];
foreach ($selected_products as $product => $quantity) {
    $line_items[] = [
        'price_data' => [
            'currency' => 'usd',
            'product_data' => [
                'name' => ucfirst(str_replace('_', ' ', $product))
            ],
            'unit_amount' => 1000, // Replace with actual price in cents
        ],
        'quantity' => (int) $quantity,
    ];
}

// Try to create Stripe Checkout Session
try {
    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $line_items,
        'mode' => 'payment',
        'success_url' => home_url('/booking-success/?session_id={CHECKOUT_SESSION_ID}'),
        'cancel_url' => home_url('/booking-cancel/'),
    ]);

    // Debug: Print the Stripe session
    echo "<pre>";
    print_r($checkout_session);
    echo "</pre>";

    // Redirect to Stripe Checkout
    header("Location: " . $checkout_session->url);
    exit;

} catch (\Stripe\Exception\ApiErrorException $e) {
    echo 'Stripe API Error: ' . $e->getMessage();
    exit;
}
