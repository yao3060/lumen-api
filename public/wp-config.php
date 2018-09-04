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

define( 'JWT_AUTH_SECRET_KEY', 'Z9?J2J2$lf8r`c}: !fPf[T/z}y]-`|%=cVd4/5n9vNH<ez/J-8*,[H@L+Oi+k6/' );
define( 'JWT_AUTH_CORS_ENABLE', true );

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'lumen_api');

/** MySQL database username */
define('DB_USER', 'sso_admin');

/** MySQL database password */
define('DB_PASSWORD', 'Sso20161223');

/** MySQL hostname */
define('DB_HOST', '192.168.101.112');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'o2T^`_n.NyR,0O;4s)&I*,}wJ<cKuK8NhD+Y:p(kmk3,<HUKy8v/:ioP?$d{NyV3');
define('SECURE_AUTH_KEY',  'Lp7n$Eu brEF1%(rk.W/yD&`yG@F)A(UkT.J hfd&$WM8Dn8yGAtWT@Xf}eprict');
define('LOGGED_IN_KEY',    'lx=Zd7R6IPh=gj_iY5$aEh%,(77NIV#H<(^_2g8`3x!rqeZg8#pX$sskeD>S8&T^');
define('NONCE_KEY',        'mOI</7%C(9dl~D}cb2~eZRd}NKlsU/]n8OgU),FB~wVPc.V(%yhA~@wPZ72/?!4G');
define('AUTH_SALT',        '@])A 1}PQ=0u85/W@I9>K2Ms[Eo+75eV[IY,M8M]5PuxYF_$-=]j&vy&KLkmTBg ');
define('SECURE_AUTH_SALT', 'ybu)pDor>AY+lMtRUmd`jmgULqvat4f#6;Dty__-}qQ*H l9VD/I27LQ{CMAr0X#');
define('LOGGED_IN_SALT',   '^*8?i#_dk fA=VZ#@Y>bpbbb:o@s:7~y1Sr&l%r!jw~T5GuTp(xbsj2(CWjBB}U(');
define('NONCE_SALT',       'XS pc1sMespl3v75.sC$r+WQy*gbZP^pi5kYTV^4PR_RU>wLL~J^E`7f)-Ui<(}?');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');

if( isset($_SERVER['REQUEST_URI']) && strpos( $_SERVER['REQUEST_URI'], 'api') !== false ) {
    require_once( ABSPATH . 'api.php');
}

/** Sets up WordPress vars and included files. */
//require_once(ABSPATH . 'wp-settings.php');
