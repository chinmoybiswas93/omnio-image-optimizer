<?php
/**
 * Statistics endpoint.
 *
 * @package OmnioImageOptimizer
 */

namespace OmnioImageOptimizer\App\Http\Controllers;

use OmnioImageOptimizer\Api\Resource\StatsResource;
use OmnioImageOptimizer\App\Services\Backup\BackupManager;
use WP_REST_Response;

if ( ! defined('ABSPATH')) {
    exit;
}

/**
 * Aggregate savings across the library.
 */
class StatsController extends Controller {

    /**
     * The stats aggregate.
     *
     * Forces a fresh read rather than serving the transient: the dashboard
     * asks for this right after a run, when a stale total is the one thing
     * the user will notice.
     *
     * @return WP_REST_Response
     */
    public function index() {
        $stats                 = StatsResource::get(true);
        $stats['backup_bytes'] = BackupManager::disk_usage();

        return $this->sendSuccess($stats);
    }
}
