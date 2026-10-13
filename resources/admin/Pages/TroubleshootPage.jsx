/** Troubleshoot tab: what this server can do, and what the plugin just did. */

import { useState, useEffect, useCallback, useRef } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';
import {
	Button,
	ConfirmDialog,
	Notice,
	Section,
	Spinner,
	Stat,
	Toggle,
	useToast,
} from '../Components';
import { IconDocument, IconLayers, IconStethoscope } from '../Icons';
import DiagnosticRow from '../Partials/DiagnosticRow';
import { request, saveSettings } from '../Services/http';
import config from '../Services/config';
import { formatBytes } from '../Services/format';

const TroubleshootPage = ( { values, setValues } ) => {
	const [ report, setReport ] = useState( null );
	const [ log, setLog ] = useState( { lines: [], size: 0, rotated: false } );
	const [ filter, setFilter ] = useState( '' );
	const [ busy, setBusy ] = useState( '' );
	const [ confirming, setConfirming ] = useState( false );
	const [ error, setError ] = useState( '' );
	const viewer = useRef( null );
	const toast = useToast();

	const enabled = !! Number( values.enable_log );

	const loadReport = useCallback( async () => {
		try {
			setReport( await request( 'diagnostics' ) );
		} catch ( e ) {
			setError( e.message );
		}
	}, [] );

	const loadLog = useCallback( async () => {
		try {
			setLog( await request( 'logs?lines=500' ) );
		} catch ( e ) {
			setError( e.message );
		}
	}, [] );

	useEffect( () => {
		loadReport();
		loadLog();
	}, [ loadReport, loadLog ] );

	// Keep the newest line in view, the way a terminal would.
	useEffect( () => {
		if ( viewer.current ) {
			viewer.current.scrollTop = viewer.current.scrollHeight;
		}
	}, [ log, filter ] );

	const toggleLogging = async ( on ) => {
		const next = { ...values, enable_log: on ? 1 : 0 };

		setBusy( 'toggle' );
		setError( '' );
		try {
			// The whole settings object is sent because the sanitizer rebuilds
			// it from the input - a partial save would reset every other value.
			await saveSettings( next );
			setValues( next );
			await loadLog();
		} catch ( e ) {
			setError( e.message );
		}
		setBusy( '' );
	};

	const reset = async () => {
		setBusy( 'reset' );
		try {
			const result = await request( 'logs/reset', { method: 'POST' } );
			setLog( result.log );
			setConfirming( false );
			toast.push( __( 'Log cleared.', 'pixelkiln-image-optimizer' ) );
		} catch ( e ) {
			setError( e.message );
			setConfirming( false );
		}
		setBusy( '' );
	};

	const requeue = async () => {
		setBusy( 'requeue' );
		try {
			const result = await request( 'requeue', { method: 'POST' } );
			toast.push(
				sprintf(
					/* translators: %d: number of images returned to the queue. */
					__(
						'%d image(s) will be tried again on the next bulk run.',
						'pixelkiln-image-optimizer'
					),
					result.requeued
				)
			);
			await loadReport();
		} catch ( e ) {
			setError( e.message );
		}
		setBusy( '' );
	};

	const rescan = async () => {
		setBusy( 'rescan' );
		try {
			const result = await request( 'rescan', { method: 'POST' } );
			toast.push(
				0 === result.cleared
					? sprintf(
							/* translators: %d: number of records checked. */
							__(
								'Checked %d record(s). Every optimized file is present.',
								'pixelkiln-image-optimizer'
							),
							result.checked
					  )
					: sprintf(
							/* translators: 1: records checked, 2: records cleared. */
							__(
								'Checked %1$d record(s) and cleared %2$d whose file was missing. Those images are pending again.',
								'pixelkiln-image-optimizer'
							),
							result.checked,
							result.cleared
					  )
			);
			await loadReport();
		} catch ( e ) {
			setError( e.message );
		}
		setBusy( '' );
	};

	const cleanup = async () => {
		setBusy( 'cleanup' );
		try {
			const result = await request( 'cleanup', { method: 'POST' } );
			toast.push(
				sprintf(
					/* translators: %d: number of leftover files deleted. */
					__(
						'%d leftover file(s) removed.',
						'pixelkiln-image-optimizer'
					),
					result.removed
				)
			);
			await loadReport();
		} catch ( e ) {
			setError( e.message );
		}
		setBusy( '' );
	};

	const copyReport = async () => {
		if ( ! report?.text ) {
			return;
		}

		try {
			await window.navigator.clipboard.writeText( report.text );
			toast.push(
				__(
					'Diagnostics copied to the clipboard.',
					'pixelkiln-image-optimizer'
				)
			);
		} catch ( e ) {
			setError(
				__(
					'Could not copy. Select the text and copy it manually.',
					'pixelkiln-image-optimizer'
				)
			);
		}
	};

	const downloadUrl = `${
		config.restUrl
	}logs/download?_wpnonce=${ encodeURIComponent( config.nonce ) }`;

	const visible = filter
		? log.lines.filter( ( line ) =>
				line.toLowerCase().includes( filter.toLowerCase() )
		  )
		: log.lines;

	return (
		<>
			{ /* Errors stay put: they describe something still wrong. */ }
			{ error && (
				<Notice status="error" onRemove={ () => setError( '' ) }>
					{ error }
				</Notice>
			) }

			<Section
				icon={ <IconStethoscope /> }
				title={ __( 'Server information', 'pixelkiln-image-optimizer' ) }
				description={ __(
					'What this server can and cannot do for image conversion.',
					'pixelkiln-image-optimizer'
				) }
			>
				{ ! report && <Spinner /> }

				{ report?.sections?.map( ( section ) => (
					<div className="pkio-diagnostics" key={ section.title }>
						<h3 className="pkio-diagnostics__title">
							{ section.title }
						</h3>
						{ section.rows.map( ( row ) => (
							<DiagnosticRow row={ row } key={ row.label } />
						) ) }
					</div>
				) ) }

				<div className="pkio-actions">
					<Button
						variant="secondary"
						onClick={ copyReport }
						disabled={ ! report }
					>
						{ __( 'Copy for support', 'pixelkiln-image-optimizer' ) }
					</Button>
					<Button variant="tertiary" onClick={ loadReport }>
						{ __( 'Refresh', 'pixelkiln-image-optimizer' ) }
					</Button>
				</div>
			</Section>

			<Section
				icon={ <IconDocument /> }
				title={ __( 'Activity log', 'pixelkiln-image-optimizer' ) }
				description={ __(
					'A step-by-step record of every conversion, written to a file.',
					'pixelkiln-image-optimizer'
				) }
			>
				<Toggle
					label={ __( 'Enable logging', 'pixelkiln-image-optimizer' ) }
					help={ __(
						'Records every step of every optimization: backup, encode, file rename, URL rewrite and file deletion. Failures are always recorded, even with this off. The file is capped at 10MB and never leaves your server.',
						'pixelkiln-image-optimizer'
					) }
					checked={ enabled }
					disabled={ 'toggle' === busy }
					onChange={ toggleLogging }
				/>

				<div className="pkio-logtoolbar">
					<input
						type="search"
						className="pkio-logtoolbar__filter"
						placeholder={ __(
							'Filter lines…',
							'pixelkiln-image-optimizer'
						) }
						value={ filter }
						onChange={ ( e ) => setFilter( e.target.value ) }
					/>
					<span className="pkio-muted">
						{ sprintf(
							/* translators: 1: number of lines shown, 2: log file size. */
							__( '%1$d lines · %2$s', 'pixelkiln-image-optimizer' ),
							visible.length,
							formatBytes( log.size )
						) }
					</span>
				</div>

				<div className="pkio-logviewer" ref={ viewer } tabIndex={ 0 }>
					{ visible.length === 0 && (
						<p className="pkio-muted">
							{ enabled
								? __(
										'Nothing recorded yet. Optimize an image and refresh.',
										'pixelkiln-image-optimizer'
								  )
								: __(
										'Nothing recorded yet. Turn logging on to capture the full detail of each conversion.',
										'pixelkiln-image-optimizer'
								  ) }
						</p>
					) }
					{ visible.map( ( line, i ) => (
						<div
							className={ `pkio-logviewer__line${
								line.includes( ' ERROR ' ) ? ' is-error' : ''
							}${ line.includes( ' WARN ' ) ? ' is-warn' : '' }` }
							key={ i }
						>
							{ line }
						</div>
					) ) }
				</div>

				{ log.rotated && (
					<p className="pkio-muted">
						{ __(
							'The log reached its 10MB limit and rolled over. Only the most recent file is shown; the download includes what is on disk now.',
							'pixelkiln-image-optimizer'
						) }
					</p>
				) }

				<div className="pkio-actions">
					<Button variant="secondary" onClick={ loadLog }>
						{ __( 'Refresh', 'pixelkiln-image-optimizer' ) }
					</Button>
					<Button
						variant="secondary"
						href={ downloadUrl }
						disabled={ ! log.size }
					>
						{ __( 'Download', 'pixelkiln-image-optimizer' ) }
					</Button>
					<Button
						variant="secondary"
						isDestructive
						onClick={ () => setConfirming( true ) }
						disabled={ 'reset' === busy || ! log.size }
					>
						{ 'reset' === busy ? (
							<Spinner />
						) : (
							__( 'Reset log', 'pixelkiln-image-optimizer' )
						) }
					</Button>
				</div>
			</Section>

			<Section
				icon={ <IconLayers /> }
				title={ __( 'Maintenance', 'pixelkiln-image-optimizer' ) }
				description={ __(
					'Recover images that were passed over because of a temporary problem.',
					'pixelkiln-image-optimizer'
				) }
			>
				<div className="pkio-stats">
					<Stat
						label={ __(
							'Images worth trying again',
							'pixelkiln-image-optimizer'
						) }
						value={ report?.retryable ?? '—' }
						tone={ report?.retryable > 0 ? 'warning' : '' }
					/>
					<Stat
						label={ __(
							'Leftover temporary files',
							'pixelkiln-image-optimizer'
						) }
						value={ report?.tempFiles ?? '—' }
						tone={ report?.tempFiles > 0 ? 'warning' : '' }
					/>
				</div>
				<p className="pkio-lede">
					{ __(
						'Images skipped because the server ran out of memory or disk space, or because no engine was available, are not retried automatically. Once the cause is fixed, this returns them to the queue. Images skipped because they genuinely cannot be improved are left alone.',
						'pixelkiln-image-optimizer'
					) }
				</p>
				<p className="pkio-lede">
					{ __(
						'Temporary files are left behind when a conversion is interrupted, for example by a server timeout. They are cleared automatically once a day, and can be cleared now.',
						'pixelkiln-image-optimizer'
					) }
				</p>
				<p className="pkio-lede">
					{ __(
						'After restoring a site, the database and the uploads folder can come from different points in time, leaving images recorded as optimized whose files are no longer there. Rescanning checks every recorded result against the disk and returns any that no longer exist to the queue. Results whose files are intact are left untouched.',
						'pixelkiln-image-optimizer'
					) }
				</p>
				<div className="pkio-actions">
					<Button
						variant="secondary"
						onClick={ requeue }
						disabled={ 'requeue' === busy || ! report?.retryable }
					>
						{ 'requeue' === busy ? (
							<Spinner />
						) : (
							__(
								'Requeue for another attempt',
								'pixelkiln-image-optimizer'
							)
						) }
					</Button>
					<Button
						variant="secondary"
						onClick={ rescan }
						disabled={ 'rescan' === busy }
					>
						{ 'rescan' === busy ? (
							<Spinner />
						) : (
							__(
								'Rescan library against disk',
								'pixelkiln-image-optimizer'
							)
						) }
					</Button>
					<Button
						variant="secondary"
						onClick={ cleanup }
						disabled={ 'cleanup' === busy || ! report?.tempFiles }
					>
						{ 'cleanup' === busy ? (
							<Spinner />
						) : (
							__(
								'Clean up temporary files',
								'pixelkiln-image-optimizer'
							)
						) }
					</Button>
				</div>
			</Section>

			{ confirming && (
				<ConfirmDialog
					title={ __( 'Reset the log?', 'pixelkiln-image-optimizer' ) }
					confirmLabel={ __( 'Reset log', 'pixelkiln-image-optimizer' ) }
					isDestructive
					busy={ 'reset' === busy }
					onConfirm={ reset }
					onCancel={ () => setConfirming( false ) }
				>
					<p>
						{ __(
							'This permanently deletes the log file and everything recorded in it. Your images and backups are unaffected.',
							'pixelkiln-image-optimizer'
						) }
					</p>
					<p>
						{ __(
							'Download the log first if you still need it for support.',
							'pixelkiln-image-optimizer'
						) }
					</p>
				</ConfirmDialog>
			) }
		</>
	);
};

export default TroubleshootPage;
