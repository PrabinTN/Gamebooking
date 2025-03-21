<?php
require_once __DIR__ . '/stripe-config.php';

header('Content-Type: application/json');

try {
    $bookingData = json_decode(file_get_contents('php://input'), true);

    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [
            [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Game Booking',
                    ],
                    'unit_amount' => $bookingData['amount'] * 100, // Stripe uses cents
                ],
                'quantity' => 1,
            ],
        ],
        'mode' => 'payment',
        'success_url' => site_url('/booking-success/?session_id={CHECKOUT_SESSION_ID}'),
        'cancel_url' => site_url('/booking-failed/'),
        'metadata' => [
            'booking_date' => $bookingData['booking_date'],
            'adults' => $bookingData['adults'],
            'kids' => $bookingData['kids'],
            'selected_games' => json_encode($bookingData['selected_games']),
        ],
    ]);

    echo json_encode(['id' => $session->id]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
