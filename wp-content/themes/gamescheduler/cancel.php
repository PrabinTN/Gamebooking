<?php
/**
 * Template Name: Payment Canceled
 */
get_header();
?>

<div class="container text-center">
    <h2>Payment Canceled</h2>
    <p>Your payment was not completed.</p>
    <a href="<?php echo site_url('/booking'); ?>" class="btn btn-primary">Try Again</a>
</div>

<?php get_footer(); ?>
