<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'playzone' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define('STRIPE_PUBLISHABLE_KEY', 'pk_test_51R4a81Inize6CwjaDOokYNzvz055umsWpJtaZR15Wa0l9431xLetGkD6ePG7jha7QRA6tnHna9wEXiUMnDqOPJWj00yHzILTt8');
define('STRIPE_SECRET_KEY', 'sk_test_51R4a81Inize6Cwja5N24b3fIOTbkP9p14lozmdwA1nyjssNNpyA0jPTe0w7z1BIwfzoWFq3tc8eSPvyaItq6oyXh00PzLcUtEm');

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'q78iF&w>W<}nAnH|-K$9f]QJfS.1w$EuO`j)Nj%Wr5E6q}msZ_~P+E+*p! /pv5b' );
define( 'SECURE_AUTH_KEY',  ':W2E1ce@?u<+o65W-%Qks2Q*7-!nri,B<i]]>8CX*H2_jazz)c5>L@[>&-$d?&.1' );
define( 'LOGGED_IN_KEY',    'J>5)f0)73dW{qic,b4^nwv)AND%E`T_8Ei&KOz.JTF6K3Dv5YH/-MMZZL290.E(P' );
define( 'NONCE_KEY',        'eGcRH*>~a47qaaCXW^B2CZ3o>V>|[RpPxxa^6Q6h_{F[s<aC1%lBa{h1N3D&)}56' );
define( 'AUTH_SALT',        '4:%MAy}YL^OFU# AZD#eXiGO|XUSS`#~|rBx/WU=s#MOW[];c+b(B0LXP&Uz&;PS' );
define( 'SECURE_AUTH_SALT', 'F=^2zuM!?o#[Eh0U}zVOEy7|FMN5&xRUM?(GHY}J&iQih^7gN)w)ThT:{oFB*nx}' );
define( 'LOGGED_IN_SALT',   '7jdr5kLa|/NKq!go#T+-2>9i4B~n!6Jl|KX}/16C)3&~AA8uw3V$Ov3N$[_eY4du' );
define( 'NONCE_SALT',       'U(xm2mJPLTPx*AT9|06w-Z4#4f!Bki}MaiJqg)EOIfO?DZ8}nz#x*+?+}39+W ws' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'pz_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
