<?php
/**
 * Notice-suppression suite for ForeignNoticeHandler. Needs no database.
 *
 * Runs against WordPress's real WP_Hook and plugin.php rather than a stub of
 * them, because the whole handler is an assertion about how WP_Hook stores
 * callbacks: it enumerates $wp_filter and removes by callback. A hand-rolled
 * hook emulator would prove nothing about the structure it actually walks.
 *
 * The classification is the part worth guarding. Keeping a foreign notice is a
 * cosmetic regression; dropping one of the plugin's own is not, because the
 * missing-build notice is the only thing that can speak when the React bundle
 * is absent.
 *
 *   php tests/php/notice-strip-test.php
 *
 * @package OmnioImageOptimizer
 */

// phpcs:disable WordPress.WP.AlternativeFunctions, WordPress.PHP.DevelopmentFunctions, WordPress.Security.EscapeOutput, WordPress.NamingConventions.PrefixAllGlobals, Squiz.Commenting, Generic.Commenting, WordPress.PHP.DiscouragedPHPFunctions, Universal.Files.SeparateFunctionsFromOO, WordPress.WP.GlobalVariablesOverride

require __DIR__ . '/bootstrap.php';

$oio_plugin_dir = dirname( __DIR__, 2 );
$oio_wp_root    = dirname( $oio_plugin_dir, 3 );

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', $oio_wp_root . '/' );
}

require $oio_wp_root . '/wp-includes/plugin.php';
require $oio_plugin_dir . '/vendor/autoload.php';

/*
 * The one WordPress function the handler calls that plugin.php does not carry.
 * Backed by a global so the screen guard can be tested both ways.
 */
$GLOBALS['oio_test_screen_id'] = 'media_page_omnio-image-optimizer';

if ( ! function_exists( 'get_current_screen' ) ) {
	function get_current_screen() {
		return (object) array( 'id' => $GLOBALS['oio_test_screen_id'] );
	}
}

function omnio_io_test_own_notice() {}
function oio_test_other_plugin_notice() {}

class OIO_Test_Foreign_Notices {

	public function license_mismatch() {}

	public static function upsell() {}
}

use OmnioImageOptimizer\App\Hooks\Handlers\ForeignNoticeHandler;
use OmnioImageOptimizer\App\Hooks\Handlers\MenuHandler;
use OmnioImageOptimizer\App\Hooks\Handlers\NoticeHandler;

/**
 * Every callback currently registered on a hook, rendered as a sortable name.
 *
 * @param string $hook Hook name.
 * @return string[]
 */
function oio_test_survivors( $hook ) {
	$names = array();

	if ( empty( $GLOBALS['wp_filter'][ $hook ] ) ) {
		return $names;
	}

	foreach ( $GLOBALS['wp_filter'][ $hook ]->callbacks as $callbacks ) {
		foreach ( $callbacks as $callback ) {
			$function = $callback['function'];

			if ( is_string( $function ) ) {
				$names[] = $function;
			} elseif ( is_array( $function ) ) {
				$names[] = ( is_object( $function[0] ) ? get_class( $function[0] ) : $function[0] ) . '::' . $function[1];
			} else {
				$names[] = 'Closure';
			}
		}
	}

	sort( $names );

	return $names;
}

Harness::suite( 'Foreign notices are cleared from the plugin screen' );

$oio_hooks = array( 'admin_notices', 'all_admin_notices', 'user_admin_notices', 'network_admin_notices' );

$oio_foreign = new OIO_Test_Foreign_Notices();
$oio_notice  = new NoticeHandler();
$oio_menu    = new MenuHandler();

foreach ( $oio_hooks as $oio_hook ) {
	// Foreign, in every shape a plugin can register one.
	add_action( $oio_hook, array( $oio_foreign, 'license_mismatch' ) );
	add_action( $oio_hook, array( 'OIO_Test_Foreign_Notices', 'upsell' ), 20 );
	add_action( $oio_hook, 'OIO_Test_Foreign_Notices::upsell', 30 );
	add_action( $oio_hook, 'oio_test_other_plugin_notice' );
	add_action(
		$oio_hook,
		function () {}
	);
	// Core's own update nag rides the same hook and goes with the rest.
	add_action( $oio_hook, 'update_nag', 5 );

	// Ours, in every shape this plugin uses.
	add_action( $oio_hook, array( $oio_notice, 'maybe_render_no_engine_notice' ) );
	add_action( $oio_hook, array( $oio_menu, 'missing_build_notice' ) );
	add_action( $oio_hook, 'omnio_io_test_own_notice' );
	add_action( $oio_hook, array( NoticeHandler::class, 'maybe_render_no_engine_notice' ), 40 );
}

( new ForeignNoticeHandler() )->strip_foreign_notices();

$oio_expected = array(
	MenuHandler::class . '::missing_build_notice',
	NoticeHandler::class . '::maybe_render_no_engine_notice',
	NoticeHandler::class . '::maybe_render_no_engine_notice',
	'omnio_io_test_own_notice',
);
sort( $oio_expected );

foreach ( $oio_hooks as $oio_hook ) {
	$oio_survivors = oio_test_survivors( $oio_hook );

	Harness::same(
		$oio_expected,
		$oio_survivors,
		$oio_hook . ': the plugin\'s four callbacks survive and the six foreign ones do not'
	);
}

Harness::suite( 'What the suppression must never do' );

/*
 * The missing-build notice is the one that cannot be lost. When the bundle is
 * absent React renders nothing, so this notice is the only thing between the
 * user and a blank screen - which is the whole reason the handler removes by
 * callback instead of calling remove_all_actions().
 */
Harness::ok(
	false !== has_action( 'admin_notices', array( $oio_menu, 'missing_build_notice' ) ),
	'the missing-build notice survives, with no bundle it is the only voice left'
);

// A closure carries no name to attribute it by, so it counts as foreign.
Harness::same(
	array(),
	array_filter(
		oio_test_survivors( 'admin_notices' ),
		function ( $name ) {
			return 'Closure' === $name;
		}
	),
	'a closure is treated as foreign, since nothing identifies it as ours'
);

// Core screens belong to whoever put a notice there.
$GLOBALS['oio_test_screen_id'] = 'upload';
add_action( 'admin_notices', 'oio_test_other_plugin_notice' );
( new ForeignNoticeHandler() )->strip_foreign_notices();

Harness::ok(
	false !== has_action( 'admin_notices', 'oio_test_other_plugin_notice' ),
	'the Media Library is untouched: only the plugin\'s own screen is cleared'
);

$GLOBALS['oio_test_screen_id'] = 'plugins';
( new ForeignNoticeHandler() )->strip_foreign_notices();

Harness::ok(
	false !== has_action( 'admin_notices', 'oio_test_other_plugin_notice' ),
	'the plugin list is untouched too'
);

Harness::suite( 'The escape hatch' );

$GLOBALS['oio_test_screen_id'] = 'media_page_omnio-image-optimizer';
add_action( 'admin_notices', array( $oio_foreign, 'license_mismatch' ) );
add_filter(
	'omnio_io_hide_foreign_notices',
	function () {
		return false;
	}
);

( new ForeignNoticeHandler() )->strip_foreign_notices();

Harness::ok(
	false !== has_action( 'admin_notices', array( $oio_foreign, 'license_mismatch' ) ),
	'omnio_io_hide_foreign_notices=false gives every notice back'
);

Harness::summary();
