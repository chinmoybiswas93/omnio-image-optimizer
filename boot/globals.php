<?php
/**
 * Global helper functions.
 *
 * Autoloaded by Composer, so these are available from the moment
 * vendor/autoload.php is required. Declares functions only.
 *
 * @package OmnioImageOptimizer
 */

use OmnioImageOptimizer\App\App;
use OmnioImageOptimizer\App\Services\Logging\Logger;

if ( ! defined('ABSPATH')) {
    exit;
}

if ( ! function_exists('omnio_io_app')) {
    /**
     * The running application, or a service resolved from it.
     *
     * @param string|false $module Binding key, or false for the application.
     * @return mixed
     */
    function omnio_io_app( $module = false ) {
        if ($module) {
            return App::make($module);
        }

        return App::getInstance();
    }
}

if ( ! function_exists('omnio_io_config')) {
    /**
     * Read a config value by dot-notated key.
     *
     * @param string $key     Dot-notated key, e.g. 'app.slug'.
     * @param mixed  $default Returned when the key is absent.
     * @return mixed
     */
    function omnio_io_config( $key, $default = null ) {
        return App::config()->get($key, $default);
    }
}

if ( ! function_exists('omnio_io_view')) {
    /**
     * Render a view and return it as a string.
     *
     * @param string               $view View path, dot or slash notation.
     * @param array<string, mixed> $data Variables for the template.
     * @return string
     */
    function omnio_io_view( $view, array $data = [] ) {
        return App::view()->make($view, $data);
    }
}

if ( ! function_exists('omnio_io_log')) {
    /**
     * Write a line to the plugin log.
     *
     * @param string $message Message to record.
     * @param string $channel Channel the entry belongs to.
     * @return void
     */
    function omnio_io_log( $message, $channel = 'general' ) {
        Logger::mark($channel, $message);
    }
}
