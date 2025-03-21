<?php
/*
Template Name: Booking Success
*/
get_header();

require_once __DIR__ . '/stripe-config.php';

$session_id = $_GET['session_id'] ?? '';

if ($session_id) {
    $session = \Stripe\Checkout\Session::retrieve($session_id);
    $metadata = $session->metadata;

    // Save booking data in WordPress admin
    wp_insert_post([
        'post_type' => 'bookings',
        'post_title' => 'Booking - ' . $metadata->booking_date,
        'post_status' => 'publish',
        'meta_input' => [
            'booking_date' => $metadata->booking_date,
            'adults' => $metadata->adults,
            'kids' => $metadata->kids,
            'selected_games' => json_decode($metadata->selected_games, true),
            'payment_status' => 'Paid',
            'transacted_total_amount' => $session->amount_total / 100,
        ]
    ]);

    echo "<h2>Payment Successful!</h2>";
    echo "<p>Thank you for your booking on <strong>" . esc_html($metadata->booking_date) . "</strong></p>";
} else {
    echo "<h2>Payment Failed!</h2>";
    echo "<p>We couldn't verify your payment details.</p>";
}

get_footer();
?>
