<?php
/**
 * Container bindings.
 *
 * The shared service instances the rest of the plugin resolves by name. These
 * were previously AppServiceProvider::register().
 *
 * @package PixelKilnImageOptimizer
 */

use PixelKilnImageOptimizer\Api\StoreSettings;
use PixelKilnImageOptimizer\App\App;
use PixelKilnImageOptimizer\App\Services\AttachmentConverter;
use PixelKilnImageOptimizer\App\Services\Bulk\Coordinator;
use PixelKilnImageOptimizer\App\Services\Bulk\Runner;
use PixelKilnImageOptimizer\App\Services\Bulk\ScanRunner;
use PixelKilnImageOptimizer\App\Services\Optimizer;
use PixelKilnImageOptimizer\App\Services\Rewrite\DatabaseRewriter;

if ( ! defined('ABSPATH')) {
    exit;
}

App::singleton('rewriter', static function () {
    return new DatabaseRewriter();
});

App::singleton('optimizer', static function () {
    return new Optimizer();
});

App::singleton('converter', static function () {
    return new AttachmentConverter(App::make('optimizer'), App::make('rewriter'));
});

App::singleton('runner', static function () {
    return new Runner(App::make('converter'), App::make('rewriter'));
});

App::singleton('scanner', static function () {
    return new ScanRunner();
});

App::singleton('coordinator', static function () {
    return new Coordinator(App::make('scanner'), App::make('runner'));
});

App::singleton('settings', static function () {
    return new StoreSettings();
});

// The concrete class names resolve to the same shared instances, so anything
// resolved by constructor type hint gets the singleton rather than a new one.
App::alias('rewriter', DatabaseRewriter::class);
App::alias('optimizer', Optimizer::class);
App::alias('converter', AttachmentConverter::class);
App::alias('runner', Runner::class);
App::alias('scanner', ScanRunner::class);
App::alias('coordinator', Coordinator::class);
App::alias('settings', StoreSettings::class);
