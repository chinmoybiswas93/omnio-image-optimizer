<?php
/**
 * Media-editor policy.
 *
 * @package PixelKilnImageOptimizer
 */

namespace PixelKilnImageOptimizer\App\Http\Policies;

use PixelKilnImageOptimizer\App\Http\Requests\OptimizeRequest;
use WP_REST_Request;

if ( ! defined('ABSPATH')) {
    exit;
}

/**
 * Guards per-attachment actions, which an editor may perform without being an
 * administrator - optimizing or restoring a single image from the Media
 * Library is ordinary editorial work.
 *
 * Replaces the old Controller::can_edit_media(). Both capabilities are
 * required: upload_files alone would let a contributor-like role act on
 * attachments it has no business editing.
 *
 * The global capabilities are not enough on their own. An Author has both,
 * yet may not edit another user's attachment - and converting one deletes its
 * original file. Every ID in the request must pass edit_post, the same check
 * the Media Library row and bulk actions make, and one failure refuses the
 * whole request rather than running the part that happens to be allowed.
 */
class MediaPolicy extends Policy {

    /**
     * {@inheritDoc}
     *
     * @param WP_REST_Request $request Request.
     * @return bool
     */
    public function verifyRequest( WP_REST_Request $request ) {
        if ( ! current_user_can('upload_files') || ! current_user_can('edit_posts')) {
            return false;
        }

        foreach (OptimizeRequest::sanitizeIds($request->get_param('ids')) as $id) {
            if ( ! current_user_can('edit_post', $id)) {
                return false;
            }
        }

        return true;
    }
}
