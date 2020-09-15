<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'creative_catering' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'vY,H ~2uKXM#cN`=f!EA[g?qFZN?beY&!lB=woPda0*[fQ0%/ qtu~l^Tf;$;pJs' );
define( 'SECURE_AUTH_KEY',  'm3*V{Z(LKKf_f-wvY^!:AdzRT4Z04%&mu`~YRnC8-880NYaykQ1b]wy{@Q;4.J)|' );
define( 'LOGGED_IN_KEY',    's&nqB,JAU/|Fshvk=Fj)A~4N_9m ErxqSh 9!BiL4r|PfK?yc-gIFXyqj_aKJFa{' );
define( 'NONCE_KEY',        'oNpl)DEUyep[ xJ_AeYeN mlqnH`c{l=u4m+I,*K?,AlS3Mca:;YW}REfh[}g}Xg' );
define( 'AUTH_SALT',        'Ote7lYycnwB^}o?SO&B|K>xe<2yEZ2%Qbosxf-MGhs(@,][!B8V(k-Zc&V%$y^+e' );
define( 'SECURE_AUTH_SALT', '!m<k|,u`fh(JmpfQw{^j+SClA>8L-4Dt5`>sI{O<(.+.@;lucQh`Iy?z77i8ZZ`T' );
define( 'LOGGED_IN_SALT',   'wR:2Om_Y6xp!GE)#w3{D~uN(EH!9GQz]yG_.())K:Le|A?;K u]2VnUTzBc8d*Z8' );
define( 'NONCE_SALT',       'g=uU7j)Eprv(5|!=FMQDjn1zo?,G8<B(L=+]{dI+Z=3wCkF%cYG2LP4Y?1(X&t+$' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'cc_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

define('WP_CACHE', false);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
