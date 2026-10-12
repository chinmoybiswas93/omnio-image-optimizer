<?php
/**
 * Action registrations.
 *
 * The plugin's registration manifest: one readable list of everything that
 * wires itself into WordPress. This replaces the eight service providers the
 * plugin used to boot through - each of which existed only to hold one or two
 * of these lines.
 *
 * Required by Framework\Application::requireCommonFiles(). $app is in scope.
 *
 * @package OmnioImageOptimizer
 */

use OmnioImageOptimizer\App\App;
use OmnioImageOptimizer\App\Hooks\CLI\Commands;
use OmnioImageOptimizer\App\Hooks\Handlers\AssetHandler;
use OmnioImageOptimizer\App\Hooks\Handlers\EditorAssetHandler;
use OmnioImageOptimizer\App\Hooks\Handlers\ForeignNoticeHandler;
use OmnioImageOptimizer\App\Hooks\Handlers\MediaLibraryHandler;
use OmnioImageOptimizer\App\Hooks\Handlers\MenuHandler;
use OmnioImageOptimizer\App\Hooks\Handlers\NoticeHandler;
use OmnioImageOptimizer\App\Hooks\Handlers\RestFieldHandler;
use OmnioImageOptimizer\App\Hooks\Scheduler\BulkJobRunner;
use OmnioImageOptimizer\App\Hooks\Scheduler\JobRunner;
use OmnioImageOptimizer\App\Hooks\Scheduler\ScanJobRunner;
use OmnioImageOptimizer\Api\StoreSettings;
use OmnioImageOptimizer\App\Services\Engine\EngineFactory;
use OmnioImageOptimizer\App\Services\Logging\Logger;
use OmnioImageOptimizer\App\Services\Rewrite\Fallback404;
use OmnioImageOptimizer\App\Services\Upload\Interceptor;

if ( ! defined('ABSPATH')) {
    exit;
}

/*
 * Settings. register_setting() expects to run on init, so this manifest only
 * schedules it - unlike the rest of the file, which registers immediately
 * because add_action/add_filter are safe at any point before their hook fires.
 */
add_action('init', static function () {
    ( new StoreSettings() )->register();
});

// A settings change can alter which engine gets selected.
add_action('update_option_' . StoreSettings::OPTION, [ EngineFactory::class, 'reset' ]);

// ... and whether logging is on, which Logger caches for the request.
add_action('update_option_' . StoreSettings::OPTION, [ Logger::class, 'settings_changed' ], 10, 2);

// ... and how often the library is rescanned, which is a cron schedule.
add_action('update_option_' . StoreSettings::OPTION, [ ScanJobRunner::class, 'settings_changed' ], 10, 2);

/*
 * Uploads. Intercepts new attachments so they are converted on the way in.
 */
( new Interceptor(App::make('optimizer')) )->register();

/*
 * REST field for the block editor. Deliberately outside the is_admin() block
 * below: register_rest_field must run on every request, including
 * /wp-json/wp/v2/media, which is never is_admin().
 */
( new RestFieldHandler() )->register();

/*
 * Backups. Daily retention sweep.
 */
( new JobRunner() )->register();

/*
 * Bulk. Advances an active run from cron, so it survives the tab closing.
 */
( new BulkJobRunner() )->register();

/*
 * Library scan. Advances a running scan from cron, and starts one on whatever
 * schedule the scan_frequency setting names.
 */
( new ScanJobRunner() )->register();

/*
 * Sequences scan -> optimize -> scan behind the dashboard's one button. A
 * listener on the two completion actions rather than a caller, so neither the
 * runner nor the scanner needs to know the chain exists.
 */
App::make('coordinator')->register();

/*
 * URL rewriting. Serves the converted file when a pre-conversion URL is hit.
 */
( new Fallback404() )->register();

/*
 * Admin UI.
 */
add_action('init', static function () {
    if ( ! is_admin()) {
        return;
    }

    ( new NoticeHandler() )->register();
    ( new ForeignNoticeHandler() )->register();
    ( new MediaLibraryHandler() )->register();
    ( new MenuHandler() )->register();
    ( new AssetHandler() )->register();
    ( new EditorAssetHandler() )->register();
}, 5);

/*
 * WP-CLI.
 */
if (defined('WP_CLI') && WP_CLI) {
    $omnio_io_commands = App::make(Commands::class);

    WP_CLI::add_command('omnio-image-optimizer optimize', [ $omnio_io_commands, 'optimize' ]);
    WP_CLI::add_command('omnio-image-optimizer restore', [ $omnio_io_commands, 'restore' ]);
    WP_CLI::add_command('omnio-image-optimizer stats', [ $omnio_io_commands, 'stats' ]);
    WP_CLI::add_command('omnio-image-optimizer diagnostics', [ $omnio_io_commands, 'diagnostics' ]);
    WP_CLI::add_command('omnio-image-optimizer logs', [ $omnio_io_commands, 'logs' ]);
    WP_CLI::add_command('omnio-image-optimizer requeue', [ $omnio_io_commands, 'requeue' ]);
    WP_CLI::add_command('omnio-image-optimizer rescan', [ $omnio_io_commands, 'rescan' ]);
    WP_CLI::add_command('omnio-image-optimizer repair-backups', [ $omnio_io_commands, 'repair_backups' ]);
    WP_CLI::add_command('omnio-image-optimizer repair-mime', [ $omnio_io_commands, 'repair_mime' ]);
}
