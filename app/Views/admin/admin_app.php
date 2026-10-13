<?php
/**
 * Admin SPA mount point.
 *
 * The whole admin screen is one React app; this is the only markup PHP emits
 * for it. Deliberately free of WordPress admin classes beyond .wrap, which is
 * what positions the screen inside the admin body - everything inside is the
 * plugin's own design system.
 *
 * `.wp-header-end` is the exception, and it is load-bearing. WordPress moves
 * every admin notice on the page to just after that marker; with no marker it
 * falls back to the first heading inside `.wrap`, which here is the React
 * masthead's own <h1>, and a notice ends up rendered *inside* this plugin's
 * header, between the title and the tagline.
 *
 * Other plugins' notices are left alone here, as on every other screen - the
 * plugin's own two bootstrap notices (no engine available, assets not built)
 * travel the same admin_notices hook, and all of them land above the masthead.
 *
 * @var string $mountId Element ID the bundle mounts into.
 * @var string $title   Screen title, shown before the bundle boots.
 *
 * @package PixelKilnImageOptimizer
 */

if ( ! defined('ABSPATH')) {
    exit;
}

?>
<div class="wrap pkio-wrap">
	<hr class="wp-header-end">
	<div id="<?php echo esc_attr($mountId); ?>" class="pkio-app">
		<div class="pkio-app__booting" role="status" aria-live="polite">
			<span class="pkio-spinner" aria-hidden="true"></span>
			<span class="pkio-app__booting-text"><?php echo esc_html($title); ?></span>
		</div>
	</div>
</div>
