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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'hl[D.yPel}$A-,yTX/Z5z!u:llPx8!ZZ[{yyeT7<~X3EC)fgh/{<5*HN8SV/&=Jg' );
define( 'SECURE_AUTH_KEY',   '2v/y8%G0Y ^wL%`zGTU7h]itwd=}en;RR# +4.$ji(3#>>?j?>9j(m*tED/C-Kmq' );
define( 'LOGGED_IN_KEY',     '6dnx3)#Bg{7RD$ASmUbg_64]Thu,4_h?6fiYuEeE{0U`2R6W.1=`b?bh7#k4/g(7' );
define( 'NONCE_KEY',         '0:I#|S4 E[cv}^|,rpF{7(rj^EvhH(?cEjw9GMklbFjP0&q$UbHRp-w*tac1hmZ^' );
define( 'AUTH_SALT',         'I-|MVKXDV^-$@np^Sqb,/Mx4YyUjH.ktB>Dt=z:e&GC2},^FvYyVKaZ8BXSS+}<t' );
define( 'SECURE_AUTH_SALT',  'Bfr)zvF)7XzYzjp>Ac ,)P)Nu!Ph#DM%mzNX)Xwr8=?;^StRs9Yt#JPF*==,%Q0,' );
define( 'LOGGED_IN_SALT',    'hbSi[|SS4rML}44d, Ek2PW50@[g0||UsS2WG~BR^p3nOvw?p$jgIfj)aClXIC)n' );
define( 'NONCE_SALT',        'o!YrHfBbLP_xs>U+aU`,9j.iXy;8!hAwf8{^u__Brx2bvH>(+X!}:ce[58U4E2&C' );
define( 'WP_CACHE_KEY_SALT', '@{5Ooh57}iC%E|JiWDn,vVewcxJ;i@6|(>YH-A?C{ga0_aE^;nIA0llaepGec[b}' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', true );
	define( 'WP_DEBUG_LOG', true );
	define( 'WP_DEBUG_DISPLAY', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
