<?php
/**
 * GameScheduler functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package GameScheduler
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function gamescheduler_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on GameScheduler, use a find and replace
		* to change 'gamescheduler' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'gamescheduler', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'gamescheduler' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'gamescheduler_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'gamescheduler_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function gamescheduler_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'gamescheduler_content_width', 640 );
}
add_action( 'after_setup_theme', 'gamescheduler_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function gamescheduler_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'gamescheduler' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'gamescheduler' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'gamescheduler_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function gamescheduler_scripts() {
	wp_enqueue_style( 'gamescheduler-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'gamescheduler-style', 'rtl', 'replace' );

	wp_enqueue_script( 'gamescheduler-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'gamescheduler_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}


/**
 * Register Games Custom Post Type
 */
function playzone_register_games_cpt() {
    $labels = array(
        'name'               => 'Games',
        'singular_name'      => 'Game',
        'menu_name'          => 'Games',
        'all_items'          => 'All Games',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Game',
        'edit_item'          => 'Edit Game',
        'new_item'           => 'New Game',
        'view_item'          => 'View Game',
        'search_items'       => 'Search Games',
        'not_found'          => 'No games found',
        'not_found_in_trash' => 'No games found in Trash',
    );

    $args = array(
        'label'               => 'Games',
        'labels'              => $labels,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'games'),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 20,
        'supports'            => array('title', 'thumbnail', 'editor', 'custom-fields'),
        'show_in_rest'        => true, // Enable REST API
    );

    register_post_type('games', $args);
}
add_action('init', 'playzone_register_games_cpt');


/**
 * Custom Fields for Game Pricing & Modes
 */

function playzone_add_game_meta_boxes() {
    add_meta_box(
        'game_details_meta',
        'Game Details',
        'playzone_render_game_meta_box',
        'games',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'playzone_add_game_meta_boxes');

function playzone_render_game_meta_box($post) {
    // Get previously saved meta values
    $game_price_adult = get_post_meta($post->ID, '_game_price_adult', true);
    $game_price_child = get_post_meta($post->ID, '_game_price_child', true);
    $game_price_standard = get_post_meta($post->ID, '_game_price_standard', true);
    $game_modes = get_post_meta($post->ID, '_game_modes', true) ?: array();  // Default to empty array if not set

    // Nonce field for security
    wp_nonce_field('playzone_game_details', 'playzone_game_nonce');
    ?>

    <p>
        <label>Adult Price:</label>
        <input type="text" name="game_price_adult" value="<?php echo esc_attr($game_price_adult); ?>" />
    </p>

    <p>
        <label>Child Price:</label>
        <input type="text" name="game_price_child" value="<?php echo esc_attr($game_price_child); ?>" />
    </p>

    <p>
        <label>Standard Price:</label>
        <input type="text" name="game_price_standard" value="<?php echo esc_attr($game_price_standard); ?>" />
    </p>

    <p>
        <label>Game Modes:</label>
        <select name="game_modes[]" multiple="multiple">
            <?php
            // Predefined options for Game Modes
            $predefined_modes = array('1 Game', '2 Games', '30 Minutes', '60 Minutes');
            
            // Loop through the predefined modes and create options
            foreach ($predefined_modes as $mode) {
                $selected = in_array($mode, $game_modes) ? 'selected="selected"' : '';
                echo '<option value="' . esc_attr($mode) . '" ' . $selected . '>' . esc_html($mode) . '</option>';
            }
            ?>
        </select>
    </p>

    <?php
}


function playzone_save_game_meta($post_id) {
    if (!isset($_POST['playzone_game_nonce']) || !wp_verify_nonce($_POST['playzone_game_nonce'], 'playzone_game_details')) {
        return;
    }

    update_post_meta($post_id, '_game_price_adult', sanitize_text_field($_POST['game_price_adult']));
    update_post_meta($post_id, '_game_price_child', sanitize_text_field($_POST['game_price_child']));
    update_post_meta($post_id, '_game_price_standard', sanitize_text_field($_POST['game_price_standard']));
    
    $modes = array_map('sanitize_text_field', explode(',', $_POST['game_modes']));
    update_post_meta($post_id, '_game_modes', $modes);
}
add_action('save_post', 'playzone_save_game_meta');




/**
 * REST API Endpoint for Games
 */

function playzone_get_games() {
    $args = array(
        'post_type'      => 'games',
        'posts_per_page' => -1,
        'post_status'    => 'publish'
    );

    $games = new WP_Query($args);
    $game_list = array();

    if ($games->have_posts()) {
        while ($games->have_posts()) {
            $games->the_post();
            $id = get_the_ID();
            $game_list[] = array(
                'id'        => $id,
                'title'     => get_the_title(),
                'image'     => get_the_post_thumbnail_url($id, 'medium'),
                'price'     => array(
                    'adult'    => get_post_meta($id, '_game_price_adult', true),
                    'child'    => get_post_meta($id, '_game_price_child', true),
                    'standard' => get_post_meta($id, '_game_price_standard', true),
                ),
                'modes'     => get_post_meta($id, '_game_modes', true)
            );
        }
        wp_reset_postdata();
    }

    return new WP_REST_Response($game_list, 200);
}
add_action('rest_api_init', function () {
    register_rest_route('playzone/v1', '/games/', array(
        'methods'  => 'GET',
        'callback' => 'playzone_get_games',
        'permission_callback' => '__return_true',
    ));
});



/**
 * REST API for Guest Selections
 */
// Register custom endpoint for guest details
add_action('rest_api_init', function() {
    register_rest_route('playzone/v1', '/guest-selection/', [
        'methods' => 'POST',
        'callback' => 'handle_guest_selection',
        'permission_callback' => '__return_true', // Change this if you want to add permission checks
    ]);
});

// Handle guest selection and store it in session
function handle_guest_selection(WP_REST_Request $request) {
    // Get the data from the request
    $data = $request->get_json_params();

    // Start session if not already started
    if (!session_id()) {
        session_start();
    }

    // Store the data in the session
    $_SESSION['booking_date'] = sanitize_text_field($data['date']);
    $_SESSION['adults'] = intval($data['adults']);
    $_SESSION['kids'] = intval($data['kids']);
    $_SESSION['selected_time'] = sanitize_text_field($data['time']);

    return new WP_REST_Response('Guest details saved', 200);
}


/**
 * Users will select games, and their selections should be stored.
 */

function playzone_save_game_selection(WP_REST_Request $request) {
    $params = $request->get_json_params();

    if (!isset($params['selected_games']) || !is_array($params['selected_games'])) {
        return new WP_REST_Response(['error' => 'Invalid game selection'], 400);
    }

    // Store selected games in session
    $_SESSION['playzone_games'] = array_map('intval', $params['selected_games']);

    return new WP_REST_Response(['success' => true, 'selected_games' => $_SESSION['playzone_games']], 200);
}


// Register Custom REST API Route for Game Selection
function playzone_register_api_routes() {
    register_rest_route('playzone/v1', '/game-selection/', array(
        'methods' => 'POST',
        'callback' => 'playzone_handle_game_selection',
        'permission_callback' => '__return_true', // Make sure to set permission checks properly
    ));
}
add_action('rest_api_init', 'playzone_register_api_routes');

// Callback function for handling game selection
function playzone_handle_game_selection(WP_REST_Request $request) {
    // Ensure the session is started
    if (!session_id()) {
        session_start();
    }

    // Get the selected games from the request
    $selected_games = $request->get_param('selected_games');

    // If no games are selected, return an error
    if (empty($selected_games)) {
        return new WP_REST_Response('No games selected.', 400);
    }

    // Save the selected games in the session
    $_SESSION['selected_games'] = $selected_games;

    // Return a success response
    return new WP_REST_Response('Games saved successfully.', 200);
}



/**
 * Time Slot Validation on /booking Page
 */

function playzone_validate_booking(WP_REST_Request $request) {
    $params = $request->get_json_params();

    if (!isset($params['selected_times']) || !is_array($params['selected_times'])) {
        return new WP_REST_Response(['error' => 'Invalid time selection'], 400);
    }

    $selected_times = $params['selected_times'];
    $booked_times = array();

    foreach ($selected_times as $game_id => $time_slot) {
        $time_parts = explode(':', $time_slot);
        if (count($time_parts) != 2) {
            return new WP_REST_Response(['error' => 'Invalid time format for game ' . $game_id], 400);
        }

        $start_hour = intval($time_parts[0]);

        // Ensure no time conflict
        if (isset($booked_times[$start_hour])) {
            return new WP_REST_Response(['error' => 'Time conflict detected'], 400);
        }

        $booked_times[$start_hour] = true;
    }

    $_SESSION['playzone_booking'] = $selected_times;

    return new WP_REST_Response(['success' => true, 'selected_times' => $_SESSION['playzone_booking']], 200);
}

add_action('rest_api_init', function () {
    register_rest_route('playzone/v1', '/validate-booking/', array(
        'methods'  => 'POST',
        'callback' => 'playzone_validate_booking',
        'permission_callback' => '__return_true',
    ));
});



/**
 * Generate Booking URL
 */


function playzone_generate_booking_url() {
    if (!isset($_SESSION['playzone_guest'], $_SESSION['playzone_games'], $_SESSION['playzone_booking'])) {
        return new WP_REST_Response(['error' => 'Missing booking data'], 400);
    }

    $guest = $_SESSION['playzone_guest'];
    $games = $_SESSION['playzone_games'];
    $times = $_SESSION['playzone_booking'];

    $base_url = 'http://localhost/PlayZoneScheduler/book/';
    $query_params = array(
        'date' => $guest['date']
    );

    foreach ($games as $game_id) {
        $query_params["selected_products[game_$game_id]"] = 1;
    }

    foreach ($times as $game_id => $time) {
        $query_params["zone_times[game_$game_id]"] = $time;
    }

    $final_url = $base_url . '?' . http_build_query($query_params);

    return new WP_REST_Response(['success' => true, 'booking_url' => $final_url], 200);
}

add_action('rest_api_init', function () {
    register_rest_route('playzone/v1', '/generate-booking-url/', array(
        'methods'  => 'GET',
        'callback' => 'playzone_generate_booking_url',
        'permission_callback' => '__return_true',
    ));
});


/**
 * Handle Guest Selection on /guest Page
 */

function playzone_enqueue_scripts() {
    // Enqueue jQuery if not already loaded
    wp_enqueue_script('jquery');
    
    // Enqueue Flatpickr JS and CSS
    wp_enqueue_script('flatpickr-js', 'https://cdn.jsdelivr.net/npm/flatpickr', [], null, true); // Flatpickr JS
    wp_enqueue_style('flatpickr-css', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css'); // Flatpickr CSS

    // Enqueue your custom script for the guest page
    wp_enqueue_script('playzone-booking', get_template_directory_uri() . '/js/playzone-booking.js', array('jquery', 'flatpickr-js'), null, true);
    
    // Localize the script for AJAX functionality
    wp_localize_script('playzone-booking', 'playzone_api', array(
        'ajax_url' => esc_url(rest_url('playzone/v1/')),
        'nonce'    => wp_create_nonce('wp_rest'),
    ));
}
add_action('wp_enqueue_scripts', 'playzone_enqueue_scripts');

function start_session_if_needed() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'start_session_if_needed', 1);

/**
 * Save Game mode to session
 */

function save_game_mode_to_session() {
    // Ensure the session is started
    if (!session_id()) {
        session_start();
    }

    // Get the game ID, selected game mode, and prices
    $game_id = isset($_POST['game_id']) ? sanitize_text_field($_POST['game_id']) : '';
    $game_mode = isset($_POST['game_mode']) ? sanitize_text_field($_POST['game_mode']) : '';
    $adult_price = isset($_POST['adult_price']) ? floatval($_POST['adult_price']) : 0;
    $kid_price = isset($_POST['kid_price']) ? floatval($_POST['kid_price']) : 0;
    $standard_price = isset($_POST['standard_price']) ? floatval($_POST['standard_price']) : 0;

    // Save the selected game mode and price data to the session
    if (!empty($game_id) && !empty($game_mode)) {
        $_SESSION['selected_game_modes'][$game_id] = [
            'game_mode' => $game_mode,
            'adult_price' => $adult_price,
            'kid_price' => $kid_price,
            'standard_price' => $standard_price
        ];
    }

    // Return success response
    wp_send_json_success();
}

add_action('wp_ajax_save_game_mode_to_session', 'save_game_mode_to_session');
add_action('wp_ajax_nopriv_save_game_mode_to_session', 'save_game_mode_to_session');




/**
 * Custom Post Type for Bookings
 */

function create_booking_post_type() {
    register_post_type('bookings', [
        'labels'      => [
            'name'          => __('Bookings'),
            'singular_name' => __('Booking'),
        ],
        'public'      => true,
        'has_archive' => true,
        'supports'    => ['title', 'editor'],
    ]);
}
add_action('init', 'create_booking_post_type');


// Register REST Route for Payment Session Creation
add_action('rest_api_init', function () {
    register_rest_route('booking/v1', '/create-payment-session', [
        'methods' => 'POST',
        'callback' => 'create_payment_session',
    ]);
});

// Create Stripe Payment Session
function create_payment_session(WP_REST_Request $request) {
    $data = $request->get_json_params();

    // Debug log to inspect received data
    error_log('Received Data: ' . print_r($data, true));

    if (empty($data['selected_products']) || empty($data['zone_times'])) {
        return new WP_REST_Response(['error' => 'Missing required data'], 400);
    }

    \Stripe\Stripe::setApiKey('sk_test_51R4a81Inize6Cwja5N24b3fIOTbkP9p14lozmdwA1nyjssNNpyA0jPTe0w7z1BIwfzoWFq3tc8eSPvyaItq6oyXh00PzLcUtEm');

    try {
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => generate_line_items($data['selected_products'], $data['zone_times']),
            'mode' => 'payment',
            'success_url' => home_url('/payment-success?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => home_url('/payment-cancel'),
        ]);

        return new WP_REST_Response(['id' => $session->id], 200);
    } catch (\Stripe\Exception\ApiErrorException $e) {
        error_log('Stripe Error: ' . $e->getMessage()); // Debug Stripe errors
        return new WP_REST_Response(['error' => $e->getMessage()], 400);
    }
}


// Generate Line Items for Stripe Checkout
function generate_line_items($selected_products, $zone_times) {
    $line_items = [];

    foreach ($selected_products as $game_id) {
        $game_name = get_the_title($game_id); 
        $price = get_post_meta($game_id, '_game_price', true); 

        if (!$price || !$game_name) continue; // Skip invalid data

        $line_items[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $game_name,
                ],
                'unit_amount' => intval($price) * 100,  // Price in cents
            ],
            'quantity' => 1, 
        ];
    }

    return $line_items;
}


// Create Booking Table
function create_booking_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'bookings';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        booking_date date NOT NULL,
        game_title varchar(255) NOT NULL,
        game_mode varchar(100),
        price_adults decimal(10, 2),
        price_kids decimal(10, 2),
        price_standard decimal(10, 2),
        time_slot varchar(100),
        number_of_adults int NOT NULL,
        number_of_kids int NOT NULL,
        total_amount decimal(10, 2),
        transaction_status varchar(50) DEFAULT 'pending',
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_switch_theme', 'create_booking_table');

// Save Booking Data
function save_booking() {
    global $wpdb;

    $booking_date = sanitize_text_field($_POST['booking_date']);
    $games = isset($_POST['games']) ? $_POST['games'] : [];
    $total_price = floatval($_POST['total_price']);
    $adults = intval($_POST['adults']);
    $kids = intval($_POST['kids']);

    foreach ($games as $game) {
        $wpdb->insert(
            $wpdb->prefix . 'bookings',
            [
                'booking_date' => $booking_date,
                'game_title' => sanitize_text_field($game['title']),
                'game_mode' => sanitize_text_field($game['mode']),
                'price_adults' => floatval($game['adult_price']),
                'price_kids' => floatval($game['kid_price']),
                'price_standard' => floatval($game['standard_price']),
                'time_slot' => sanitize_text_field($game['time_slot']),
                'number_of_adults' => $adults,
                'number_of_kids' => $kids,
                'total_amount' => $total_price,
                'transaction_status' => 'Pending'
            ]
        );
    }

    wp_send_json_success(['message' => 'Booking saved successfully!']);
    wp_die();
}
add_action('wp_ajax_save_booking', 'save_booking');
add_action('wp_ajax_nopriv_save_booking', 'save_booking');

// Delete Booking Table on Theme Switch
function delete_booking_table() {
    global $wpdb;
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}bookings");
}
add_action('switch_theme', 'delete_booking_table');
