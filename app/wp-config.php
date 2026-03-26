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
define( 'DB_NAME', 'wordpress_db' );

/** Database username */
define( 'DB_USER', 'wp_user' );

/** Database password */
define( 'DB_PASSWORD', 'password' );

/** Database hostname */
define( 'DB_HOST', 'db' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         ';/.(@}%~Nz1Gp!TZ.HN7sxu{]sFFo_G?;-EE:K$1cPIC9)#ho5rSl5q>NgDjcZZx' );
define( 'SECURE_AUTH_KEY',  'q,4M^)RN@?<ELZ3LtoF}Fv!fDeU%bftR;)r]:BT,ZDh:s&s{RhKMB!`j~x7dV8>G' );
define( 'LOGGED_IN_KEY',    'Tz=9<=G/i!+E%/tAQz6t8-{jj?0I#eQHp:}yZQ4J!0 v?|]i/J.kog*x]{?bHd(b' );
define( 'NONCE_KEY',        'ws{X2N)h$}AGrk1!-G1995mK,cJ(Ut0FHQ5l#jd~:$}sKZoq,TI%?S?kWJZo-C!B' );
define( 'AUTH_SALT',        'XAFI,_3QL9&y:/i%YH(`Yad3Bu{j:{Q</%; kc}9p%yea>,c+,twcf<FI{ijHm`|' );
define( 'SECURE_AUTH_SALT', 'EMi3m|-ssHzor]iMg~+s1)jSkFLr#`n.6k3Wglsw`Y6~1vdpF_p>:/P&nN}>%Ftu' );
define( 'LOGGED_IN_SALT',   ';m BXVAkjzb!p:gzXH]?IX&E:$1YS<stoogTj;%_k}j|5]a_tTNm1KyLR*7o[9gW' );
define( 'NONCE_SALT',       'W-9M[]a%ZEdvtD)I~hwe_mWR|5*&e ]s*HMARcq4cI[ug:o&{eQs}5xE$|nknq;j' );

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
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */

define( 'DISALLOW_FILE_EDIT', true );
define( 'WP_POST_REVISIONS', 5 );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

