<?php
/**
 * Application bootstrap.
 *
 * Returns the closure the main plugin file invokes with __FILE__. Everything
 * the plugin does starts here.
 *
 * @package PixelKilnImageOptimizer
 */

use PixelKilnImageOptimizer\Framework\Application;
use PixelKilnImageOptimizer\App\Hooks\Handlers\ActivationHandler;
use PixelKilnImageOptimizer\App\Hooks\Handlers\DeactivationHandler;
use PixelKilnImageOptimizer\Database\DBMigrator;

if ( ! defined('ABSPATH')) {
    exit;
}

return function ( $file ) {

    // Constructing the Application also points the App facade at it, loads
    // boot/bindings.php, and requires the hook manifests.
    $app = new Application($file);

    register_activation_hook($file, function ( $networkWide = false ) use ( $app ) {
        $app->make(ActivationHandler::class)->handle($networkWide);
    });

    register_deactivation_hook($file, function () use ( $app ) {
        $app->make(DeactivationHandler::class)->handle();
    });

    add_action('plugins_loaded', function () use ( $app ) {
        do_action('pixelkiln_io/loaded', $app);

        add_action('init', function () use ( $app ) {
            // Apply any pending schema change before anything reads the tables.
            DBMigrator::migrateUp();

            do_action('pixelkiln_io/init', $app);
        }, 1);
    });

    return $app;
};
