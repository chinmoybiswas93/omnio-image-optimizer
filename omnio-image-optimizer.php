<?php
/**
 * Plugin Name:       Omnio Image Optimizer
 * Plugin URI:        https://chinmoybiswas.com/omnio-image-optimizer
 * Description:       Automatically convert and compress images to WebP on upload, plus individual and bulk optimization for your existing Media Library.
 * Version:           1.0.0
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Author:            chinmoy709
 * Author URI:        https://chinmoybiswas.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       omnio-image-optimizer
 *
 * @package OmnioImageOptimizer
 */

if (!defined('ABSPATH')) {
	exit;
}

if (!defined('OMNIO_IO_FILE')) {
	define('OMNIO_IO_VERSION', '1.0.0');
	define('OMNIO_IO_FILE', __FILE__);
	define('OMNIO_IO_DIR', plugin_dir_path(__FILE__));
	define('OMNIO_IO_URL', plugin_dir_url(__FILE__));
	define('OMNIO_IO_BASENAME', plugin_basename(__FILE__));
}

require __DIR__ . '/vendor/autoload.php';
$omnio_io_boot = require __DIR__ . '/boot/app.php';
return $omnio_io_boot(__FILE__);

