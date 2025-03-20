<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package GameScheduler
 */

?>

	<footer id="colophon" class="site-footer">
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'gamescheduler' ) ); ?>">
				<?php
				/* translators: %s: CMS name, i.e. WordPress. */
				printf( esc_html__( 'Proudly powered by %s', 'gamescheduler' ), 'WordPress' );
				?>
			</a>
			<span class="sep"> | </span>
				<?php
				/* translators: 1: Theme name, 2: Theme author. */
				printf( esc_html__( 'Theme: %1$s by %2$s.', 'gamescheduler' ), 'gamescheduler', '<a href="http://underscores.me/">Prabin Shrestha</a>' );
				?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle the Proceed to Payment button click event
    document.getElementById('proceedToPayment').addEventListener('click', function() {
        // Collect selected game data and booking info
        var selectedGames = JSON.parse(sessionStorage.getItem('selected_games')) || [];
        var selectedModes = JSON.parse(sessionStorage.getItem('selected_modes')) || {};
        var selectedPrices = JSON.parse(sessionStorage.getItem('selected_prices')) || {};
        const bookingDate = "<?php echo esc_js($booking_date); ?>";
        
        // Create payment URL based on selected games and times
        let paymentURL = "https://example.com/book/?date=" + bookingDate;
        selectedGames.forEach(gameId => {
            let gameMode = selectedModes[gameId] || 'Not Selected';
            let gameTitle = encodeURIComponent(gameId);  // Get the game title or ID
            paymentURL += `&selected_products[${gameTitle}]=1`;  // Add game to URL (example, adjust accordingly)
        });

        // Call the REST API endpoint to create the Stripe session
        fetch('<?php echo esc_url(rest_url('booking/v1/create-payment-session')); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                selected_products: selectedGames,
                zone_times: selectedModes,
                date: bookingDate,
            })
        })
        .then(response => response.json())
        .then(data => {
            const sessionId = data.id;
            
            // Use Stripe.js to redirect to the Stripe Checkout page
            var stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');
            stripe.redirectToCheckout({ sessionId: sessionId });
        })
        .catch(error => {
            console.error('Error creating Stripe session:', error);
            alert('There was an error processing your payment.');
        });
    });
});
</script>
</body>
</html>
