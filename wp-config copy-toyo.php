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

define('WP_CACHE', true);
define( 'WPCACHEHOME', '/var/www/html/nittosupport/wp-content/plugins/wp-super-cache/' );
define('WP_ALLOW_MULTISITE', true);

define('DB_NAME', 'toyosupport');

/** Database username */
define('DB_USER', 'ankur');

/** Database password */
define('DB_PASSWORD', 'Ankur@123');

/** Database hostname */
define('DB_HOST', 'localhost');

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

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
define('AUTH_KEY',         '4egZ(]v_Z-5@Ub%ZVAQo4A3TVT[^=r-?1yBA_!RgF%,<E:D!LN>+66M?p+H$42K(');
define('SECURE_AUTH_KEY',  '4HQ}w7mnQbOSUCXffD;0Y8T`UL%*ig?/cLiCWJu~No]D+%Wbcl6h,AAf@-Zn2;.g');
define('LOGGED_IN_KEY',    'UGy!{}Z>9gw|h:F55N0xPNGyP9 BN*0Mikfb}T_:2wAN=fUi>5axk+{iHol/iv.n');
define('NONCE_KEY',        'g?^?9oC?$0<!`a~+]nmrx=)6vwOng_#v&,SyV~W=rj#?TB%4D:G*U8=hM9rJDLLc');
define('AUTH_SALT',        'ytRHAZ>yA_RDkjy{#fMrzO?[P@iPK90|N=?oC?5eZ9f-Q1f@]I$kIX+ E7r{Z(R&');
define('SECURE_AUTH_SALT', 'u6@,dG|^x#Ioy{5#0,_Zr@1 :V 6:3=xP_QNYa|;7:/ PHFHtC/10BJ+,}wudZPH');
define('LOGGED_IN_SALT',   'ojVs1l|)`TmerD1*/;8oj%@Mz]xE2)Q!:~)J/@l[@4&M^(AF<l<mg`r`j*#~#Kxr');
define('NONCE_SALT',       'UD,8k,JMC2+,b]UAZlTXR@N[b4RC?@h`lRe! p7lj-hgjr{Fijwm#bc,fRDSizOw');

define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'localhost');
define('PATH_CURRENT_SITE', '/nittosupport/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

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
$table_prefix = 'mkjn_';

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
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('TITLE', 'Welcome to Nitto Support Site');

define('FS_METHOD', 'direct');

define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '256M');

@ini_set('display_errors', 1);
@ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (! defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
