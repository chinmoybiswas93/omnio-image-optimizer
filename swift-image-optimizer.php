<?php
/**
 * Plugin Name:        Image Optimizer
 * Plugin URI:        https://wpswift.com/swift-image-optimizer
 * Description:       Automatically convert and compress images to WebP on upload, plus individual and bulk optimization for your existing Media Library.
 * Version:           1.0.0
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Author:            chinmoy709
 * Author URI:        https://chinmoybiswas.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       swift-image-optimizer
 *
 * @package SwiftImageOptimizer
 */

if (!defined('ABSPATH')) {
	exit;
}

if (!defined('SWIFT_IMAGE_OPTIMIZER_PLUGIN_FILE')) {
	define('SWIFT_IMAGE_OPTIMIZER_VERSION', '1.0.0');
	define('SWIFT_IMAGE_OPTIMIZER_FILE', __FILE__);
	define('SWIFT_IMAGE_OPTIMIZER_DIR', plugin_dir_path(__FILE__));
	define('SWIFT_IMAGE_OPTIMIZER_URL', plugin_dir_url(__FILE__));
	define('SWIFT_IMAGE_OPTIMIZER_BASENAME', plugin_basename(__FILE__));
}

require __DIR__ . '/vendor/autoload.php';
$boot = require __DIR__ . '/boot/app.php';
return $boot(__FILE__);

