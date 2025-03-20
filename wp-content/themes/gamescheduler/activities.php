<?php
/**
 * Template Name: Activities Page
 */
get_header();
?>

<div class="container mt-5 activities-page">
    <h2 class="text-center mb-4">Select Your Games</h2>
    <div class="row">
        <?php
        $games = get_posts(array(
            'post_type'      => 'games',
            'posts_per_page' => -1,
        ));

        if (!empty($games)) :
            foreach ($games as $game) :
                $price_adult = get_post_meta($game->ID, '_game_price_adult', true);
                $price_child = get_post_meta($game->ID, '_game_price_child', true);
                $price_standard = get_post_meta($game->ID, '_game_price_standard', true);
        ?>

                <div class="col-md-4 mb-4">
                    <div class="card shadow-lg game-card">
                        <?php echo get_the_post_thumbnail($game->ID, 'medium', array('class' => 'card-img-top', 'alt' => esc_attr($game->post_title))); ?>
                        <div class="card-body text-center">
                            <h5 class="card-title"> <?php echo esc_html($game->post_title); ?> </h5>
                            <p class="card-text text-muted">
                                <?php
                                $prices = array();
                                if (!empty($price_adult)) $prices[] = "Adult: $price_adult";
                                if (!empty($price_child)) $prices[] = "Child: $price_child";
                                if (!empty($price_standard)) $prices[] = "Standard: $price_standard";
                                echo implode(" | ", $prices);
                                ?>
                            </p>
                            <div class="game-select-wrapper mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input game-checkbox" id="game_<?php echo esc_attr($game->ID); ?>" value="<?php echo esc_attr($game->ID); ?>" data-adult="<?php echo esc_attr($price_adult); ?>" data-child="<?php echo esc_attr($price_child); ?>" data-standard="<?php echo esc_attr($price_standard); ?>" />
                                    <label for="game_<?php echo esc_attr($game->ID); ?>" class="form-check-label added-text">Select</label>
                                </div>
                                <div>
                                    <select class="form-select game-mode" id="game_modes_<?php echo esc_attr($game->ID); ?>" name="game_modes[<?php echo esc_attr($game->ID); ?>]">
                                        <option value="1 Game">1 Game</option>
                                        <option value="2 Games">2 Games</option>
                                        <option value="30 Minutes">30 Minutes</option>
                                        <option value="60 Minutes">60 Minutes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        <?php endforeach;
        else : ?>
            <p class="text-center">No games available.</p>
        <?php endif; ?>
    </div>
    <div class="text-center mt-4">
        <button id="nextToBooking" class="btn btn-primary btn-lg btn-next">Next</button>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll('.game-checkbox');
    const nextButton = document.getElementById('nextToBooking');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const label = this.nextElementSibling;
            label.textContent = this.checked ? "Added" : "Select";
            label.classList.toggle('text-success', this.checked);
        });
    });

    nextButton.addEventListener('click', function () {
        let selectedGames = [];
        let selectedModes = {};
        let selectedPrices = {};

        document.querySelectorAll('.game-checkbox:checked').forEach(checkbox => {
            let gameId = checkbox.value;
            let gameMode = document.getElementById('game_modes_' + gameId).value;
            let priceAdult = checkbox.dataset.adult || "0";
            let priceChild = checkbox.dataset.child || "0";
            let priceStandard = checkbox.dataset.standard || "0";

            selectedGames.push(gameId);
            selectedModes[gameId] = gameMode;
            selectedPrices[gameId] = { adult: priceAdult, child: priceChild, standard: priceStandard };
        });

        if (selectedGames.length === 0) {
            alert("Please select at least one game.");
            return;
        }

        sessionStorage.setItem('selected_games', JSON.stringify(selectedGames));
        sessionStorage.setItem('selected_modes', JSON.stringify(selectedModes));
        sessionStorage.setItem('selected_prices', JSON.stringify(selectedPrices));

        window.location.href = '<?php echo get_site_url(); ?>/PlayZoneScheduler/booking/';
    });
});
</script>

<?php get_footer(); ?>






