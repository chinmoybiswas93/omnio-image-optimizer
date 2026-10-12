<?php
/**
 * Enqueues the block editor integration assets.
 *
 * @package OmnioImageOptimizer
 */

namespace OmnioImageOptimizer\App\Hooks\Handlers;

use OmnioImageOptimizer\App\App;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Loads the Image/Gallery block sidebar panel.
 *
 * A separate bundle from AssetHandler's, on purpose: that one enqueues on
 * `admin_enqueue_scripts`, gated on the classic media-views script being
 * present, which is not a reliable signal inside the block editor. This
 * hooks `enqueue_block_editor_assets` directly instead.
 */
class EditorAssetHandler {

	/**
	 * Script handle.
	 */
	const HANDLE = 'omnio-image-optimizer-editor';

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue' ) );
	}

	/**
	 * Enqueue the block editor bundle.
	 *
	 * @return void
	 */
	public function enqueue() {
		if ( ! current_user_can( 'upload_files' ) || ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$asset_file = OMNIO_IO_DIR . 'build/editor.asset.php';

		if ( ! file_exists( $asset_file ) ) {
			return;
		}

		$asset = include $asset_file;

		wp_enqueue_script(
			self::HANDLE,
			OMNIO_IO_URL . 'build/editor.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);

		wp_enqueue_style(
			self::HANDLE,
			OMNIO_IO_URL . 'build/editor.css',
			array(),
			$asset['version']
		);

		wp_set_script_translations( self::HANDLE, 'omnio-image-optimizer' );

		/*
		 * Only the namespace, not a full REST URL or nonce: this bundle only
		 * ever runs inside the block editor, where @wordpress/api-fetch
		 * already has WordPress's own root-URL and nonce middleware wired up
		 * globally. Passing the namespace keeps it the single source of
		 * truth for the route prefix, matching api.php's own note that these
		 * URLs are load-bearing.
		 */
		wp_localize_script(
			self::HANDLE,
			'omnioImageOptimizerEditor',
			array(
				'namespace' => App::router()->getNamespace(),
			)
		);
	}
}
