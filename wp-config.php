<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'practice_task' );

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
define( 'AUTH_KEY',         '&PT(Z{%/Sy(#G@cU1E7pk]QV 1UI?Y=h)I!pK9Nc}W]n6qV/RoVNVsh]cK`qh)`f' );
define( 'SECURE_AUTH_KEY',  ';;cdFENU_pxcOzX^XG!r(wbf[I<lAJjtCM~[R_sFQNND7R~F@Knj!M;2))I8}JpC' );
define( 'LOGGED_IN_KEY',    'b273zW2t9GI^b.eGfVuL3%!Zi@e)@POEVNRoa$D_;h+w**HQ7QA}c.~C)U#aB/)0' );
define( 'NONCE_KEY',        'NB_ Br.1f?Nx[ bmOf,x&*Y8ghG*fXZ3w_ jr fDA^HWBRSp2A}Db4rfewr1F6+/' );
define( 'AUTH_SALT',        'R;-Tp_#x{W%hW^Wv]vzwDBEm*q|^KMIIQ=@C//M_8]R87,(7G03ZASJY7AvG8a-M' );
define( 'SECURE_AUTH_SALT', 'Y<y}_wF=f^W[`D$,<z0G<u6= C;.9Gt0Px%Qt)rz_**(R0?&me}x8<5f<n^|afj{' );
define( 'LOGGED_IN_SALT',   'VzJqeot-Lm?jv}_u11ePruJVE{u!)dZ=aZ.ypN_P~ yq00U7(`3T?|~[hyc)R@5)' );
define( 'NONCE_SALT',       '87^(] ENLk(~cUoN*Fv8;@;g(Uwrs&{Tv@0>0oI<(WDc]zf{EByD6&DwCVX}+Guc' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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

