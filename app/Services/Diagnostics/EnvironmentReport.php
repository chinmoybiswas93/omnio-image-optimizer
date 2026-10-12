<?php
/**
 * Server and plugin environment report.
 *
 * @package OmnioImageOptimizer
 */

namespace OmnioImageOptimizer\App\Services\Diagnostics;

use OmnioImageOptimizer\App\Models\OptimizationLog;
use OmnioImageOptimizer\Database\DBMigrator;
use OmnioImageOptimizer\Api\StoreSettings;
use OmnioImageOptimizer\App\Services\Backup\BackupManager;
use OmnioImageOptimizer\App\Services\Bulk\Scanner;
use OmnioImageOptimizer\App\Services\Engine\EngineFactory;
use OmnioImageOptimizer\App\Services\Logging\Logger;
use OmnioImageOptimizer\App\Hooks\Scheduler\JobRunner;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Answers "why did this fail on my host" without shell access.
 *
 * Every row carries a state and, where the state is not OK, a remedy written
 * for a site owner rather than a developer - the copy rule from ui-context.md.
 */
class EnvironmentReport {

	/**
	 * Row states.
	 */
	const OK    = 'ok';
	const WARN  = 'warn';
	const ERROR = 'error';

	/**
	 * Build the full report.
	 *
	 * @return array{sections: array, generated: string}
	 */
	public static function get() {
		return array(
			'generated' => current_time( 'mysql' ),
			'sections'  => array(
				self::engines(),
				self::php(),
				self::filesystem(),
				self::wordpress(),
				self::plugin(),
			),
		);
	}

	/**
	 * Conversion engine availability and why each one is or is not usable.
	 *
	 * @return array
	 */
	private static function engines() {
		$available = EngineFactory::availability();
		$active    = EngineFactory::get();
		$preferred = StoreSettings::get( 'engine', 'auto' );

		$rows = array();

		$rows[] = self::row(
			__( 'Active engine', 'omnio-image-optimizer' ),
			$active ? $active->name() : __( 'None', 'omnio-image-optimizer' ),
			$active ? self::OK : self::ERROR,
			$active ? '' : __( 'No image conversion engine was found. Ask your host to enable the Imagick extension, or GD with WebP support.', 'omnio-image-optimizer' )
		);

		if ( 'auto' !== $preferred ) {
			$honored = isset( $available[ $preferred ] ) && $available[ $preferred ];

			$rows[] = self::row(
				__( 'Engine preference', 'omnio-image-optimizer' ),
				$preferred,
				$honored ? self::OK : self::WARN,
				$honored ? '' : __( 'The engine chosen in Settings is not available here, so the best available one is being used instead.', 'omnio-image-optimizer' )
			);
		}

		$rows[] = self::row(
			__( 'Imagick', 'omnio-image-optimizer' ),
			self::yes_no( $available['imagick'] ),
			$available['imagick'] ? self::OK : self::WARN,
			$available['imagick'] ? '' : self::imagick_reason()
		);

		if ( $available['imagick'] ) {
			$rows[] = self::row( __( 'ImageMagick version', 'omnio-image-optimizer' ), self::imagick_version(), self::OK, '' );
		}

		$rows[] = self::row(
			__( 'cwebp binary', 'omnio-image-optimizer' ),
			$available['cwebp'] ? self::cwebp_path() : self::yes_no( false ),
			$available['cwebp'] ? self::OK : self::WARN,
			$available['cwebp'] ? '' : self::cwebp_reason()
		);

		$rows[] = self::row(
			__( 'GD with WebP support', 'omnio-image-optimizer' ),
			self::yes_no( $available['gd'] ),
			$available['gd'] ? self::OK : self::WARN,
			$available['gd'] ? '' : __( 'GD is missing or was built without WebP support. It is the universal fallback, so this is worth asking your host about.', 'omnio-image-optimizer' )
		);

		$rows[] = self::row(
			__( 'Reads EXIF orientation', 'omnio-image-optimizer' ),
			self::yes_no( function_exists( 'exif_read_data' ) ),
			function_exists( 'exif_read_data' ) ? self::OK : self::WARN,
			function_exists( 'exif_read_data' ) ? '' : __( 'Without the EXIF extension, photos taken in portrait may be saved sideways when GD is the active engine.', 'omnio-image-optimizer' )
		);

		return self::section( __( 'Conversion engines', 'omnio-image-optimizer' ), $rows );
	}

	/**
	 * PHP limits that decide whether a large image can be processed at all.
	 *
	 * @return array
	 */
	private static function php() {
		$memory      = wp_convert_hr_to_bytes( (string) ini_get( 'memory_limit' ) );
		$max_execute = (int) ini_get( 'max_execution_time' );
		$disabled    = trim( (string) ini_get( 'disable_functions' ) );

		$rows = array(
			self::row( __( 'PHP version', 'omnio-image-optimizer' ), PHP_VERSION, version_compare( PHP_VERSION, '7.4', '>=' ) ? self::OK : self::ERROR, '' ),
			self::row(
				__( 'Memory limit', 'omnio-image-optimizer' ),
				(string) ini_get( 'memory_limit' ),
				( $memory <= 0 || $memory >= 268435456 ) ? self::OK : self::WARN,
				( $memory <= 0 || $memory >= 268435456 ) ? '' : __( 'Large images may be skipped. 256M or more is recommended; ask your host to raise it.', 'omnio-image-optimizer' )
			),
			self::row(
				__( 'Max execution time', 'omnio-image-optimizer' ),
				0 === $max_execute ? __( 'Unlimited', 'omnio-image-optimizer' ) : $max_execute . 's',
				( 0 === $max_execute || $max_execute >= 30 ) ? self::OK : self::WARN,
				( 0 === $max_execute || $max_execute >= 30 ) ? '' : __( 'Bulk optimization will process fewer images per request. It will still finish, it will just take more steps.', 'omnio-image-optimizer' )
			),
			self::row( __( 'Upload max filesize', 'omnio-image-optimizer' ), (string) ini_get( 'upload_max_filesize' ), self::OK, '' ),
			self::row( __( 'Post max size', 'omnio-image-optimizer' ), (string) ini_get( 'post_max_size' ), self::OK, '' ),
			self::row(
				__( 'Disabled PHP functions', 'omnio-image-optimizer' ),
				'' === $disabled ? __( 'None', 'omnio-image-optimizer' ) : $disabled,
				'' === $disabled ? self::OK : self::WARN,
				'' === $disabled ? '' : __( 'Some functions are blocked by the host. This only matters if it prevents the cwebp binary from running.', 'omnio-image-optimizer' )
			),
		);

		return self::section( __( 'PHP', 'omnio-image-optimizer' ), $rows );
	}

	/**
	 * Directories the plugin has to be able to write to.
	 *
	 * @return array
	 */
	private static function filesystem() {
		$uploads = wp_get_upload_dir();
		$free    = self::free_space();
		$log_dir = Logger::ensure_dir();

		$uploads_writable = empty( $uploads['error'] ) && wp_is_writable( $uploads['basedir'] );
		$backup_writable  = BackupManager::ensure_root();

		$rows = array(
			self::row(
				__( 'Uploads directory writable', 'omnio-image-optimizer' ),
				self::yes_no( $uploads_writable ),
				$uploads_writable ? self::OK : self::ERROR,
				$uploads_writable ? '' : __( 'Nothing can be converted until the uploads folder is writable. Ask your host to correct its permissions.', 'omnio-image-optimizer' )
			),
			self::row(
				__( 'Backup directory writable', 'omnio-image-optimizer' ),
				self::yes_no( $backup_writable ),
				$backup_writable ? self::OK : self::ERROR,
				$backup_writable ? '' : __( 'Originals cannot be backed up, so conversions of existing images will be refused rather than risk being irreversible.', 'omnio-image-optimizer' )
			),
			self::row(
				__( 'Log directory writable', 'omnio-image-optimizer' ),
				self::yes_no( ! is_wp_error( $log_dir ) ),
				is_wp_error( $log_dir ) ? self::WARN : self::OK,
				is_wp_error( $log_dir ) ? __( 'Diagnostic logging is unavailable because the log folder could not be created.', 'omnio-image-optimizer' ) : ''
			),
			self::row(
				__( 'Free disk space', 'omnio-image-optimizer' ),
				$free > 0 ? size_format( $free ) : __( 'Unknown', 'omnio-image-optimizer' ),
				( $free > 0 && $free < 524288000 ) ? self::WARN : self::OK,
				( $free > 0 && $free < 524288000 ) ? __( 'Less than 500MB free. Backups need roughly as much space as the images being converted.', 'omnio-image-optimizer' ) : ''
			),
			self::row( __( 'Backup storage used', 'omnio-image-optimizer' ), size_format( BackupManager::disk_usage() ), self::OK, '' ),
			self::row( __( 'Log file size', 'omnio-image-optimizer' ), size_format( Logger::size() ), self::OK, '' ),
			self::row( __( 'Uploads path', 'omnio-image-optimizer' ), $uploads['basedir'], self::OK, '' ),
		);

		return self::section( __( 'Filesystem', 'omnio-image-optimizer' ), $rows );
	}

	/**
	 * WordPress-side facts that change how the plugin behaves.
	 *
	 * @return array
	 */
	private static function wordpress() {
		$cron_disabled = defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON;
		$next_purge    = wp_next_scheduled( JobRunner::HOOK );

		$rows = array(
			self::row( __( 'WordPress version', 'omnio-image-optimizer' ), get_bloginfo( 'version' ), self::OK, '' ),
			self::row(
				__( 'Multisite', 'omnio-image-optimizer' ),
				self::yes_no( is_multisite() ),
				is_multisite() ? self::WARN : self::OK,
				is_multisite() ? __( 'This plugin has not been tested on a multisite network.', 'omnio-image-optimizer' ) : ''
			),
			self::row( __( 'Debug mode', 'omnio-image-optimizer' ), self::yes_no( defined( 'WP_DEBUG' ) && WP_DEBUG ), self::OK, '' ),
			self::row(
				__( 'Persistent object cache', 'omnio-image-optimizer' ),
				self::yes_no( wp_using_ext_object_cache() ),
				self::OK,
				''
			),
			self::row(
				__( 'WP-Cron', 'omnio-image-optimizer' ),
				$cron_disabled ? __( 'Disabled', 'omnio-image-optimizer' ) : __( 'Enabled', 'omnio-image-optimizer' ),
				$cron_disabled ? self::WARN : self::OK,
				$cron_disabled ? __( 'Expired backups will not be cleaned up automatically unless a real cron job runs WordPress scheduled tasks.', 'omnio-image-optimizer' ) : ''
			),
			self::row(
				__( 'Next backup cleanup', 'omnio-image-optimizer' ),
				$next_purge ? wp_date( 'Y-m-d H:i', $next_purge ) : __( 'Not scheduled', 'omnio-image-optimizer' ),
				$next_purge ? self::OK : self::WARN,
				$next_purge ? '' : __( 'The cleanup task is not scheduled. Deactivating and reactivating the plugin will restore it.', 'omnio-image-optimizer' )
			),
		);

		return self::section( __( 'WordPress', 'omnio-image-optimizer' ), $rows );
	}

	/**
	 * The plugin's own state.
	 *
	 * @return array
	 */
	private static function plugin() {
		$summary = Scanner::summary();
		$counts  = self::status_counts();

		$rows = array(
			self::row( __( 'Plugin version', 'omnio-image-optimizer' ), OMNIO_IO_VERSION, self::OK, '' ),
			self::row( __( 'Database schema version', 'omnio-image-optimizer' ), (string) get_option( DBMigrator::SCHEMA_OPTION, 0 ), self::OK, '' ),
			self::row( __( 'Convertible images', 'omnio-image-optimizer' ), (string) $summary['total'], self::OK, '' ),
			self::row( __( 'Pending', 'omnio-image-optimizer' ), (string) $summary['pending'], self::OK, '' ),
			self::row( __( 'Optimized', 'omnio-image-optimizer' ), (string) $counts[ OptimizationLog::STATUS_OPTIMIZED ], self::OK, '' ),
			self::row( __( 'Skipped', 'omnio-image-optimizer' ), (string) $counts[ OptimizationLog::STATUS_SKIPPED ], self::OK, '' ),
			self::row(
				__( 'Failed', 'omnio-image-optimizer' ),
				(string) $counts[ OptimizationLog::STATUS_FAILED ],
				$counts[ OptimizationLog::STATUS_FAILED ] > 0 ? self::WARN : self::OK,
				$counts[ OptimizationLog::STATUS_FAILED ] > 0 ? __( 'Turn on logging and use Requeue below to try these again.', 'omnio-image-optimizer' ) : ''
			),
			self::row( __( 'Restored', 'omnio-image-optimizer' ), (string) $counts[ OptimizationLog::STATUS_RESTORED ], self::OK, '' ),
			self::row(
				__( 'Diagnostic logging', 'omnio-image-optimizer' ),
				Logger::is_enabled() ? __( 'On', 'omnio-image-optimizer' ) : __( 'Off (errors only)', 'omnio-image-optimizer' ),
				self::OK,
				''
			),
		);

		return self::section( __( 'Plugin', 'omnio-image-optimizer' ), $rows );
	}

	/**
	 * Row counts per status.
	 *
	 * @return array<string, int>
	 */
	private static function status_counts() {
		global $wpdb;

		$counts = array(
			OptimizationLog::STATUS_OPTIMIZED => 0,
			OptimizationLog::STATUS_SKIPPED   => 0,
			OptimizationLog::STATUS_FAILED    => 0,
			OptimizationLog::STATUS_RESTORED  => 0,
		);

		$table = OptimizationLog::table();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter -- Plugin-owned table; diagnostics screen, always read live.
		$rows = $wpdb->get_results( "SELECT status, COUNT(*) AS total FROM {$table} GROUP BY status", ARRAY_A );

		if ( ! is_array( $rows ) ) {
			return $counts;
		}

		foreach ( $rows as $row ) {
			$counts[ $row['status'] ] = (int) $row['total'];
		}

		return $counts;
	}

	/**
	 * Why Imagick is unusable here.
	 *
	 * @return string
	 */
	private static function imagick_reason() {
		if ( ! extension_loaded( 'imagick' ) || ! class_exists( 'Imagick' ) ) {
			return __( 'The Imagick extension is not installed. It is the best option because it is the only engine that preserves colour profiles.', 'omnio-image-optimizer' );
		}

		return __( 'Imagick is installed but was built without WebP support. Ask your host to add the WebP delegate.', 'omnio-image-optimizer' );
	}

	/**
	 * ImageMagick version string.
	 *
	 * @return string
	 */
	private static function imagick_version() {
		try {
			$version = \Imagick::getVersion();
		} catch ( \Exception $e ) {
			return __( 'Unknown', 'omnio-image-optimizer' );
		}

		return isset( $version['versionString'] ) ? (string) $version['versionString'] : __( 'Unknown', 'omnio-image-optimizer' );
	}

	/**
	 * Why cwebp is unusable here.
	 *
	 * @return string
	 */
	private static function cwebp_reason() {
		if ( ! function_exists( 'exec' ) ) {
			return __( 'Running external programs is disabled on this server, so the cwebp binary cannot be used. This is common on shared hosting and is not a problem as long as another engine is available.', 'omnio-image-optimizer' );
		}

		return __( 'No cwebp binary was found on this server. This plugin does not ship one, and does not need it when Imagick or GD is available.', 'omnio-image-optimizer' );
	}

	/**
	 * Path to the cwebp binary, for display.
	 *
	 * @return string
	 */
	private static function cwebp_path() {
		/** This filter is documented in src/Services/Engine/CwebpEngine.php */
		$configured = apply_filters( 'omnio_io_cwebp_binary', '' );

		if ( $configured && is_executable( $configured ) ) {
			return (string) $configured;
		}

		foreach ( array( '/usr/bin/cwebp', '/usr/local/bin/cwebp', '/opt/homebrew/bin/cwebp', '/opt/local/bin/cwebp' ) as $candidate ) {
			if ( is_executable( $candidate ) ) {
				return $candidate;
			}
		}

		return __( 'Found', 'omnio-image-optimizer' );
	}

	/**
	 * Free space on the uploads volume.
	 *
	 * @return int Bytes, or 0 when it cannot be determined.
	 */
	private static function free_space() {
		if ( ! function_exists( 'disk_free_space' ) ) {
			return 0;
		}

		$uploads = wp_get_upload_dir();

		// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- open_basedir restrictions make this fail on some hosts; handled by the false check.
		$free = @disk_free_space( $uploads['basedir'] );

		return $free ? (int) $free : 0;
	}

	/**
	 * Build a section.
	 *
	 * @param string $title Section heading.
	 * @param array  $rows  Rows.
	 * @return array
	 */
	private static function section( $title, array $rows ) {
		return array(
			'title' => $title,
			'rows'  => $rows,
		);
	}

	/**
	 * Build a row.
	 *
	 * @param string $label Row label.
	 * @param string $value Displayed value.
	 * @param string $state One of the state constants.
	 * @param string $hint  Plain-language remedy, empty when the state is OK.
	 * @return array
	 */
	private static function row( $label, $value, $state, $hint ) {
		return array(
			'label' => $label,
			'value' => (string) $value,
			'state' => $state,
			'hint'  => $hint,
		);
	}

	/**
	 * Localized yes/no.
	 *
	 * @param bool $value The flag.
	 * @return string
	 */
	private static function yes_no( $value ) {
		return $value ? __( 'Yes', 'omnio-image-optimizer' ) : __( 'No', 'omnio-image-optimizer' );
	}

	/**
	 * Flatten the report to plain text, for the "copy for support" button.
	 *
	 * @return string
	 */
	public static function as_text() {
		$report = self::get();
		$out    = array( '### Omnio Image Optimizer diagnostics (' . $report['generated'] . ')' );

		foreach ( $report['sections'] as $section ) {
			$out[] = '';
			$out[] = '## ' . $section['title'];

			foreach ( $section['rows'] as $row ) {
				$line = sprintf( '%-32s %s', $row['label'] . ':', $row['value'] );

				if ( self::OK !== $row['state'] ) {
					$line .= '   [' . strtoupper( $row['state'] ) . ']';
				}

				$out[] = $line;
			}
		}

		return implode( "\n", $out );
	}
}
