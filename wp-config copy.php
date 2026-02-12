<?php
/**
 * WordPress base configuration file
 */

/* =======================
 * Database settings
 * ======================= */
define('DB_NAME', 'nittosupport');
define('DB_USER', 'ankur');
define('DB_PASSWORD', 'Ankur@123');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

/* =======================
 * Authentication keys & salts
 * ======================= */
define('AUTH_KEY',         '4egZ(]v_Z-5@Ub%ZVAQo4A3TVT[^=r-?1yBA_!RgF%,<E:D!LN>+66M?p+H$42K(');
define('SECURE_AUTH_KEY',  '4HQ}w7mnQbOSUCXffD;0Y8T`UL%*ig?/cLiCWJu~No]D+%Wbcl6h,AAf@-Zn2;.g');
define('LOGGED_IN_KEY',    'UGy!{}Z>9gw|h:F55N0xPNGyP9 BN*0Mikfb}T_:2wAN=fUi>5axk+{iHol/iv.n');
define('NONCE_KEY',        'g?^?9oC?$0<!`a~+]nmrx=)6vwOng_#v&,SyV~W=rj#?TB%4D:G*U8=hM9rJDLLc');
define('AUTH_SALT',        'ytRHAZ>yA_RDkjy{#fMrzO?[P@iPK90|N=?oC?5eZ9f-Q1f@]I$kIX+ E7r{Z(R&');
define('SECURE_AUTH_SALT', 'u6@,dG|^x#Ioy{5#0,_Zr@1 :V 6:3=xP_QNYa|;7:/ PHFHtC/10BJ+,}wudZPH');
define('LOGGED_IN_SALT',   'ojVs1l|)`TmerD1*/;8oj%@Mz]xE2)Q!:~)J/@l[@4&M^(AF<l<mg`r`j*#~#Kxr');
define('NONCE_SALT',       'UD,8k,JMC2+,b]UAZlTXR@N[b4RC?@h`lRe! p7lj-hgjr{Fijwm#bc,fRDSizOw');

/* =======================
 * Multisite configuration
 * ======================= */
define('WP_ALLOW_MULTISITE', true);
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'localhost');
define('PATH_CURRENT_SITE', '/nittosupport/');  // Changed from /mysite/ to match your .htaccess
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

/* =======================
 * Cookie handling (CRITICAL for localhost multisite)
 * ======================= */
define('COOKIE_DOMAIN', '');
define('COOKIEPATH', '/');
define('SITECOOKIEPATH', '/');

/* =======================
 * Debug & performance
 * ======================= */
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('WP_CACHE', false);

/* =======================
 * Important: Site URL definitions
 * ======================= */
define('WP_HOME', 'http://localhost/nittosupport');
define('WP_SITEURL', 'http://localhost/nittosupport');

/* =======================
 * Memory & filesystem
 * ======================= */
define('FS_METHOD', 'direct');
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '256M');

/* =======================
 * Database table prefix
 * ======================= */
$table_prefix = 'wpntts_';

/* =======================
 * Disable SSL for localhost
 * ======================= */
define('FORCE_SSL_ADMIN', false);
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
    define('FORCE_SSL_ADMIN', true);
} else {
    $_SERVER['HTTPS'] = 'off';
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';