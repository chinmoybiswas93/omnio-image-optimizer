<?php
/**
 * Uninstall routine.
 *
 * Removes the plugin's own data. User media is never touched, and backups are
 * left in place so an accidental uninstall cannot destroy original images.
 *
 * @package PixelKilnImageOptimizer
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

// WordPress runs this file without loading the plugin, so the autoloader has
// to be pulled in by hand. The migrator resolves its table names through the
// models, so a single-file require is no longer enough.
require_once __DIR__ . '/vendor/autoload.php';

\PixelKilnImageOptimizer\Database\DBMigrator::dropTables();

/**
 * Remove one of the plugin's own working directories inside uploads.
 *
 * Logs and scratch files are the plugin's own output and hold nothing the user
 * would want back, so unlike backups they go. Resolved without loading any
 * plugin class, which would drag in the autoloader and the settings.
 *
 * @param string $name Directory name inside uploads.
 * @return void
 */
function pixelkiln_io_remove_dir( $name ) {
	$uploads = wp_get_upload_dir();

	if ( ! empty( $uploads['error'] ) ) {
		return;
	}

	$dir = trailingslashit( $uploads['basedir'] ) . $name;

	if ( ! is_dir( $dir ) ) {
		return;
	}

	$files = glob( $dir . '/*' );

	if ( is_array( $files ) ) {
		foreach ( $files as $file ) {
			if ( is_file( $file ) ) {
				wp_delete_file( $file );
			}
		}
	}

	@rmdir( $dir ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_rmdir, WordPress.PHP.NoSilencedErrors.Discouraged -- Removing the plugin's own now-empty directory during uninstall; a directory left behind by a failed removal is harmless.
}

pixelkiln_io_remove_dir( 'pixelkiln-image-optimizer/logs' );
pixelkiln_io_remove_dir( 'pixelkiln-image-optimizer/temp' );

/*
 * Then the parent, which only succeeds once it is empty.
 *
 * `pixelkiln-image-optimizer/backup` is deliberately not removed - those are the
 * user's original images and often the only copy. rmdir() refuses a directory
 * that still has anything in it, so this tidies up after an install that never
 * backed anything up, and leaves the folder alone the moment it holds
 * originals. Nothing here globs into the backup directory.
 */
pixelkiln_io_remove_dir( 'pixelkiln-image-optimizer' );

delete_option( 'pixelkiln_io_settings' );
delete_option( 'pixelkiln_io_schema_version' );
delete_option( 'pixelkiln_io_bulk_progress' );
delete_option( 'pixelkiln_io_log_suffix' );
delete_option( 'pixelkiln_io_bulk_lock' );
delete_option( 'pixelkiln_io_bulk_phase' );
delete_option( 'pixelkiln_io_library_scan' );
delete_option( 'pixelkiln_io_scan_progress' );
delete_option( 'pixelkiln_io_scan_lock' );

// Per-attachment conversion locks are options rather than transients, so they
// need clearing explicitly. Normally released as each conversion ends; a
// process killed mid-conversion can leave one behind.
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Uninstall routine; no core API deletes options by prefix.
$wpdb->query(
	$wpdb->prepare(
		"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
		$wpdb->esc_like( 'pixelkiln_io_lock_' ) . '%'
	)
);

delete_transient( 'pixelkiln_io_stats' );

wp_clear_scheduled_hook( 'pixelkiln_io_purge_backups' );
wp_clear_scheduled_hook( 'pixelkiln_io_scan_batch' );
wp_clear_scheduled_hook( 'pixelkiln_io_scheduled_scan' );
