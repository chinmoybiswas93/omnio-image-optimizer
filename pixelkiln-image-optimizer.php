<?php
/**
 * Plugin Name:       PixelKiln Image Optimizer
 * Description:       Automatically convert and compress images to WebP on upload, plus individual and bulk optimization for your existing Media Library.
 * Version:           1.0.0
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Author:            chinmoy709
 * Author URI:        https://chinmoybiswas.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       pixelkiln-image-optimizer
 *
 * @package PixelKilnImageOptimizer
 */

if (!defined('ABSPATH')) {
	exit;
}

if (!defined('PIXELKILN_IO_FILE')) {
	define('PIXELKILN_IO_VERSION', '1.0.0');
	define('PIXELKILN_IO_FILE', __FILE__);
	define('PIXELKILN_IO_DIR', plugin_dir_path(__FILE__));
	define('PIXELKILN_IO_URL', plugin_dir_url(__FILE__));
	define('PIXELKILN_IO_BASENAME', plugin_basename(__FILE__));
}

require __DIR__ . '/vendor/autoload.php';
$pixelkiln_io_boot = require __DIR__ . '/boot/app.php';
return $pixelkiln_io_boot(__FILE__);

