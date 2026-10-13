<?php
/**
 * Application configuration.
 *
 * @package PixelKilnImageOptimizer
 */

if ( ! defined('ABSPATH')) {
    exit;
}

return [
    'name'           => 'PixelKiln Image Optimizer',
    'slug'           => 'pixelkiln-image-optimizer',
    'text_domain'    => 'pixelkiln-image-optimizer',
    'domain_path'    => '/languages',

    // Every do_action/apply_filters the plugin owns is prefixed with this.
    'hook_prefix'    => 'pixelkiln_io/',

    // Together these form the REST base: /wp-json/pixelkiln-image-optimizer/v1
    // These values are load-bearing - the admin bundle calls these URLs.
    'rest_namespace' => 'pixelkiln-image-optimizer',
    'rest_version'   => 'v1',

    'env'            => 'production',
];
