/** Plugin identity: mark, wordmark and the engine currently in use. */

import { __, sprintf } from '@wordpress/i18n';
import { LogoMark } from '../Icons';
import config from '../Services/config';

const Masthead = () => (
	<div className="oio-masthead">
		<LogoMark />
		<div className="oio-masthead__text">
			<h1 className="oio-masthead__title">
				{ __( 'Omnio Image Optimizer', 'omnio-image-optimizer' ) }
			</h1>
			<p className="oio-masthead__tagline">
				{ __(
					'Convert your media library to WebP and keep it that way.',
					'omnio-image-optimizer'
				) }
			</p>
		</div>
		{ config.engine && (
			<span className="oio-pill">
				{ sprintf(
					/* translators: %s: active conversion engine. */
					__( '%s engine', 'omnio-image-optimizer' ),
					config.engine
				) }
			</span>
		) }
	</div>
);

export default Masthead;
