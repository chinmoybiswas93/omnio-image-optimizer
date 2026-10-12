<?php
/**
 * Application configuration.
 *
 * @package OmnioImageOptimizer
 */

if ( ! defined('ABSPATH')) {
    exit;
}

return [
    'name'           => 'Omnio Image Optimizer',
    'slug'           => 'omnio-image-optimizer',
    'text_domain'    => 'omnio-image-optimizer',
    'domain_path'    => '/languages',

    // Every do_action/apply_filters the plugin owns is prefixed with this.
    'hook_prefix'    => 'omnio_io/',

    // Together these form the REST base: /wp-json/omnio-image-optimizer/v1
    // These values are load-bearing - the admin bundle calls these URLs.
    'rest_namespace' => 'omnio-image-optimizer',
    'rest_version'   => 'v1',

    'env'            => 'production',
];
