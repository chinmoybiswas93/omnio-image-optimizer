/** Plugin identity: mark, wordmark and the engine currently in use. */

import { __, sprintf } from '@wordpress/i18n';
import { LogoMark } from '../Icons';
import config from '../Services/config';

const Masthead = () => (
	<div className="pkio-masthead">
		<LogoMark />
		<div className="pkio-masthead__text">
			<h1 className="pkio-masthead__title">
				{ __( 'PixelKiln Image Optimizer', 'pixelkiln-image-optimizer' ) }
			</h1>
			<p className="pkio-masthead__tagline">
				{ __(
					'Convert your media library to WebP and keep it that way.',
					'pixelkiln-image-optimizer'
				) }
			</p>
		</div>
		{ config.engine && (
			<span className="pkio-pill">
				{ sprintf(
					/* translators: %s: active conversion engine. */
					__( '%s engine', 'pixelkiln-image-optimizer' ),
					config.engine
				) }
			</span>
		) }
	</div>
);

export default Masthead;
