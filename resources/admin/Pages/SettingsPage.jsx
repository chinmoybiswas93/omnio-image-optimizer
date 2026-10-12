/** Settings tab. */

import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import {
	Button,
	Notice,
	NumberInput,
	Range,
	Section,
	Select,
	Spinner,
	Toggle,
	useToast,
} from '../Components';
import { IconGear, IconLayers, IconSliders } from '../Icons';
import { saveSettings } from '../Services/http';

const SettingsPage = ( { values, setValues } ) => {
	const [ saving, setSaving ] = useState( false );
	const [ error, setError ] = useState( '' );
	const toast = useToast();

	const set = ( key, value ) => {
		setValues( ( prev ) => ( { ...prev, [ key ]: value } ) );
	};

	/*
	 * "Saved" is a toast rather than a notice: it is true for a moment and
	 * needs no response, so it should not add a block to the top of the form
	 * and push every field down while the operator is still working.
	 */
	const save = async () => {
		setSaving( true );
		setError( '' );
		try {
			await saveSettings( values );
			toast.push( __( 'Settings saved.', 'omnio-image-optimizer' ) );
		} catch ( e ) {
			setError( e.message );
		}
		setSaving( false );
	};

	const bool = ( key ) => !! Number( values[ key ] );

	return (
		<>
			{ /* Errors stay put: they describe something still wrong. */ }
			{ error && (
				<Notice status="error" onRemove={ () => setError( '' ) }>
					{ error }
				</Notice>
			) }

			<Section
				icon={ <IconSliders /> }
				title={ __( 'Conversion', 'omnio-image-optimizer' ) }
				description={ __(
					'How images are turned into WebP.',
					'omnio-image-optimizer'
				) }
			>
				<Toggle
					label={ __(
						'Optimize new uploads automatically',
						'omnio-image-optimizer'
					) }
					help={ __(
						'Convert images to WebP as they are uploaded.',
						'omnio-image-optimizer'
					) }
					checked={ bool( 'auto_optimize' ) }
					onChange={ ( v ) => set( 'auto_optimize', v ? 1 : 0 ) }
				/>
				<Range
					label={ __( 'WebP quality', 'omnio-image-optimizer' ) }
					help={ __(
						'82 is a good balance. Below 75 shows artefacts; above 88 gains little.',
						'omnio-image-optimizer'
					) }
					value={ Number( values.quality ) || 82 }
					onChange={ ( v ) => set( 'quality', v ) }
					min={ 1 }
					max={ 100 }
				/>
				<NumberInput
					label={ __(
						'Maximum image dimension (px)',
						'omnio-image-optimizer'
					) }
					help={ __(
						'Longest edge. Set to 0 to leave dimensions alone.',
						'omnio-image-optimizer'
					) }
					value={ Number( values.max_dimension ) || 0 }
					onChange={ ( v ) =>
						set( 'max_dimension', parseInt( v, 10 ) || 0 )
					}
					min={ 0 }
					max={ 10000 }
				/>
				<Toggle
					label={ __(
						'Convert PNG images',
						'omnio-image-optimizer'
					) }
					checked={ bool( 'convert_png' ) }
					onChange={ ( v ) => set( 'convert_png', v ? 1 : 0 ) }
				/>
				<Toggle
					label={ __(
						'Keep the original when WebP would be larger',
						'omnio-image-optimizer'
					) }
					checked={ bool( 'skip_if_larger' ) }
					onChange={ ( v ) => set( 'skip_if_larger', v ? 1 : 0 ) }
				/>
				<Toggle
					label={ __(
						'Keep a backup of uploaded originals',
						'omnio-image-optimizer'
					) }
					help={ __(
						'Uploads are converted and the original file is replaced. With this on, the original is kept so the image can be restored later. Turning it off means uploaded originals are gone for good.',
						'omnio-image-optimizer'
					) }
					checked={ bool( 'backup_uploads' ) }
					onChange={ ( v ) => set( 'backup_uploads', v ? 1 : 0 ) }
				/>
				<Select
					label={ __( 'Conversion engine', 'omnio-image-optimizer' ) }
					value={ values.engine || 'auto' }
					options={ [
						{
							label: __(
								'Automatic (recommended)',
								'omnio-image-optimizer'
							),
							value: 'auto',
						},
						{ label: 'Imagick', value: 'imagick' },
						{ label: 'cwebp', value: 'cwebp' },
						{ label: 'GD', value: 'gd' },
					] }
					onChange={ ( v ) => set( 'engine', v ) }
				/>
			</Section>

			<Section
				icon={ <IconLayers /> }
				title={ __( 'Existing images', 'omnio-image-optimizer' ) }
				description={ __(
					'What happens to images already published on your site.',
					'omnio-image-optimizer'
				) }
			>
				<Toggle
					label={ __(
						'Rewrite URLs in the database',
						'omnio-image-optimizer'
					) }
					help={ __(
						'Update references in posts, meta and options when an existing image is converted. Turning this off will leave broken images.',
						'omnio-image-optimizer'
					) }
					checked={ bool( 'rewrite_urls' ) }
					onChange={ ( v ) => set( 'rewrite_urls', v ? 1 : 0 ) }
				/>
				<Toggle
					label={ __(
						'Serve the WebP when an old URL is requested',
						'omnio-image-optimizer'
					) }
					help={ __(
						'Catches references the rewriter could not reach, such as hotlinks and cached pages.',
						'omnio-image-optimizer'
					) }
					checked={ bool( 'enable_404_fallback' ) }
					onChange={ ( v ) =>
						set( 'enable_404_fallback', v ? 1 : 0 )
					}
				/>
				<Select
					label={ __(
						'Keep original backups for',
						'omnio-image-optimizer'
					) }
					help={ __(
						'After this, originals are deleted and images can no longer be restored.',
						'omnio-image-optimizer'
					) }
					value={ String( values.backup_retention ?? 30 ) }
					options={ [
						{
							label: __( '7 days', 'omnio-image-optimizer' ),
							value: '7',
						},
						{
							label: __( '30 days', 'omnio-image-optimizer' ),
							value: '30',
						},
						{
							label: __( '90 days', 'omnio-image-optimizer' ),
							value: '90',
						},
						{
							label: __(
								'Keep forever',
								'omnio-image-optimizer'
							),
							value: '0',
						},
					] }
					onChange={ ( v ) =>
						set( 'backup_retention', parseInt( v, 10 ) )
					}
				/>
				{ /*
				 * String-valued, so no parseInt on the way back - unlike
				 * backup_retention above, which stores an integer.
				 */ }
				<Select
					label={ __(
						'Scan the library automatically',
						'omnio-image-optimizer'
					) }
					help={ __(
						'The figures on the Bulk Optimize tab come from the last scan. A scan checks every image against the files on disk and runs in the background.',
						'omnio-image-optimizer'
					) }
					value={ values.scan_frequency || 'weekly' }
					options={ [
						{
							label: __( 'Manual only', 'omnio-image-optimizer' ),
							value: 'manual',
						},
						{
							label: __( 'Daily', 'omnio-image-optimizer' ),
							value: 'daily',
						},
						{
							label: __(
								'Weekly (recommended)',
								'omnio-image-optimizer'
							),
							value: 'weekly',
						},
						{
							label: __(
								'About every 30 days',
								'omnio-image-optimizer'
							),
							value: 'monthly',
						},
					] }
					onChange={ ( v ) => set( 'scan_frequency', v ) }
				/>
			</Section>

			<Section
				icon={ <IconGear /> }
				title={ __( 'WordPress behaviour', 'omnio-image-optimizer' ) }
				description={ __(
					'Core defaults this plugin can override.',
					'omnio-image-optimizer'
				) }
			>
				<Toggle
					label={ __(
						'Disable WordPress automatic image scaling',
						'omnio-image-optimizer'
					) }
					help={ __(
						'WordPress scales images over 2560px by default.',
						'omnio-image-optimizer'
					) }
					checked={ bool( 'disable_wp_scaling' ) }
					onChange={ ( v ) => set( 'disable_wp_scaling', v ? 1 : 0 ) }
				/>
			</Section>

			<div className="oio-savebar">
				<Button variant="primary" onClick={ save } disabled={ saving }>
					{ saving ? (
						<Spinner />
					) : (
						__( 'Save settings', 'omnio-image-optimizer' )
					) }
				</Button>
			</div>
		</>
	);
};

export default SettingsPage;
