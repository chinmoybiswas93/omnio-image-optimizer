/**
 * The optimization panel rendered inside a block's InspectorControls.
 *
 * Mirrors resources/media/media.js's buildPanel(), so an image reads the
 * same status whether it's opened from the classic Media Library or a block
 * in the editor — the difference between them was only ever which UI could
 * see the data, not what the data says. This version is React, because
 * InspectorControls is a React tree; media.js stays Backbone/vanilla-DOM
 * because the classic modal is.
 */

import { useEffect, useState } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';

import Button from '../admin/Components/Button';
import { fetchStatus, optimize, restore } from './actions';

const formatBytes = ( bytes ) => {
	const value = Number( bytes ) || 0;

	if ( value < 1024 ) {
		return `${ value } B`;
	}

	const units = [ 'KB', 'MB', 'GB' ];
	let size = value / 1024;
	let i = 0;

	while ( size >= 1024 && i < units.length - 1 ) {
		size /= 1024;
		i++;
	}

	return `${ size.toFixed( size < 10 ? 2 : 1 ) } ${ units[ i ] }`;
};

const STATUS_META = {
	optimized: {
		dot: 'is-success',
		label: __( 'Image Optimized', 'pixelkiln-image-optimizer' ),
	},
	skipped: {
		dot: 'is-muted',
		label: __( 'Not Optimized', 'pixelkiln-image-optimizer' ),
	},
	failed: {
		dot: 'is-danger',
		label: __( 'Optimization Failed', 'pixelkiln-image-optimizer' ),
	},
	restored: {
		dot: 'is-muted',
		label: __( 'Restored to Original', 'pixelkiln-image-optimizer' ),
	},
};

/**
 * @param {Object} props
 * @param {number} props.attachmentId Attachment ID the panel describes.
 */
export default function Panel( { attachmentId } ) {
	const [ data, setData ] = useState( undefined ); // undefined = loading, null = nothing to say.
	const [ busy, setBusy ] = useState( false );
	const [ error, setError ] = useState( '' );

	useEffect( () => {
		let cancelled = false;

		setData( undefined );
		setError( '' );

		fetchStatus( attachmentId )
			.then( ( result ) => {
				if ( ! cancelled ) {
					setData( result );
				}
			} )
			.catch( () => {
				if ( ! cancelled ) {
					setData( null );
				}
			} );

		return () => {
			cancelled = true;
		};
	}, [ attachmentId ] );

	const runAction = ( action ) => {
		setBusy( true );
		setError( '' );

		const call = 'restore' === action ? restore : optimize;

		call( attachmentId )
			.then( () => fetchStatus( attachmentId ) )
			.then( ( result ) => setData( result ) )
			.catch( ( err ) => setError( err.message ) )
			.finally( () => setBusy( false ) );
	};

	if ( undefined === data || null === data ) {
		return null;
	}

	const optimized = 'optimized' === data.status;
	const meta = STATUS_META[ data.status ];

	if ( ! optimized && ! data.canOptimize && ! data.reason ) {
		return null;
	}

	return (
		<div className={ `pkio-panel ${ meta ? `is-${ data.status }` : '' }` }>
			<div className="pkio-panel__brand">
				<span>
					{ __( 'PixelKiln Image Optimizer', 'pixelkiln-image-optimizer' ) }
				</span>
			</div>

			{ meta && (
				<div className="pkio-panel__head">
					<span className={ `pkio-panel__dot ${ meta.dot }` } />
					<strong>{ meta.label }</strong>
					{ optimized && (
						<span className="pkio-panel__percent">
							{ sprintf(
								/* translators: %s: percentage saved. */
								__( 'Saved %s%%', 'pixelkiln-image-optimizer' ),
								data.percent
							) }
						</span>
					) }
				</div>
			) }

			{ optimized && (
				<>
					<div className="pkio-panel__sizes">
						{ sprintf(
							/* translators: 1: original size, 2: optimized size. */
							__( '%1$s → %2$s', 'pixelkiln-image-optimizer' ),
							formatBytes( data.originalSize ),
							formatBytes( data.optimizedSize )
						) }
					</div>

					{ data.canRestore ? (
						<Button
							variant="tertiary"
							className="pkio-panel__restore"
							isBusy={ busy }
							onClick={ () => runAction( 'restore' ) }
						>
							{ __(
								'Restore original',
								'pixelkiln-image-optimizer'
							) }
						</Button>
					) : (
						<p className="pkio-panel__note">
							{ __(
								'No original stored, so this cannot be restored.',
								'pixelkiln-image-optimizer'
							) }
						</p>
					) }
				</>
			) }

			{ ! optimized && (
				<>
					{ data.reason && (
						<p className="pkio-panel__reason">{ data.reason }</p>
					) }

					{ data.canOptimize && (
						<Button
							variant="secondary"
							className="pkio-panel__optimize"
							isBusy={ busy }
							onClick={ () => runAction( 'optimize' ) }
						>
							{ data.reason
								? __( 'Try again', 'pixelkiln-image-optimizer' )
								: __( 'Optimize', 'pixelkiln-image-optimizer' ) }
						</Button>
					) }
				</>
			) }

			{ error && <p className="pkio-panel__error">{ error }</p> }
		</div>
	);
}
