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
			toast.push( __( 'Settings saved.', 'pixelkiln-image-optimizer' ) );
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
				title={ __( 'Conversion', 'pixelkiln-image-optimizer' ) }
				description={ __(
					'How images are turned into WebP.',
					'pixelkiln-image-optimizer'
				) }
			>
				<Toggle
					label={ __(
						'Optimize new uploads automatically',
						'pixelkiln-image-optimizer'
					) }
					help={ __(
						'Convert images to WebP as they are uploaded.',
						'pixelkiln-image-optimizer'
					) }
					checked={ bool( 'auto_optimize' ) }
					onChange={ ( v ) => set( 'auto_optimize', v ? 1 : 0 ) }
				/>
				<Range
					label={ __( 'WebP quality', 'pixelkiln-image-optimizer' ) }
					help={ __(
						'82 is a good balance. Below 75 shows artefacts; above 88 gains little.',
						'pixelkiln-image-optimizer'
					) }
					value={ Number( values.quality ) || 82 }
					onChange={ ( v ) => set( 'quality', v ) }
					min={ 1 }
					max={ 100 }
				/>
				<NumberInput
					label={ __(
						'Maximum image dimension (px)',
						'pixelkiln-image-optimizer'
					) }
					help={ __(
						'Longest edge. Set to 0 to leave dimensions alone.',
						'pixelkiln-image-optimizer'
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
						'pixelkiln-image-optimizer'
					) }
					checked={ bool( 'convert_png' ) }
					onChange={ ( v ) => set( 'convert_png', v ? 1 : 0 ) }
				/>
				<Toggle
					label={ __(
						'Keep the original when WebP would be larger',
						'pixelkiln-image-optimizer'
					) }
					checked={ bool( 'skip_if_larger' ) }
					onChange={ ( v ) => set( 'skip_if_larger', v ? 1 : 0 ) }
				/>
				<Toggle
					label={ __(
						'Keep a backup of uploaded originals',
						'pixelkiln-image-optimizer'
					) }
					help={ __(
						'Uploads are converted and the original file is replaced. With this on, the original is kept so the image can be restored later. Turning it off means uploaded originals are gone for good.',
						'pixelkiln-image-optimizer'
					) }
					checked={ bool( 'backup_uploads' ) }
					onChange={ ( v ) => set( 'backup_uploads', v ? 1 : 0 ) }
				/>
				<Select
					label={ __( 'Conversion engine', 'pixelkiln-image-optimizer' ) }
					value={ values.engine || 'auto' }
					options={ [
						{
							label: __(
								'Automatic (recommended)',
								'pixelkiln-image-optimizer'
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
				title={ __( 'Existing images', 'pixelkiln-image-optimizer' ) }
				description={ __(
					'What happens to images already published on your site.',
					'pixelkiln-image-optimizer'
				) }
			>
				<Toggle
					label={ __(
						'Rewrite URLs in the database',
						'pixelkiln-image-optimizer'
					) }
					help={ __(
						'Update references in posts, meta and options when an existing image is converted. Turning this off will leave broken images.',
						'pixelkiln-image-optimizer'
					) }
					checked={ bool( 'rewrite_urls' ) }
					onChange={ ( v ) => set( 'rewrite_urls', v ? 1 : 0 ) }
				/>
				<Toggle
					label={ __(
						'Serve the WebP when an old URL is requested',
						'pixelkiln-image-optimizer'
					) }
					help={ __(
						'Catches references the rewriter could not reach, such as hotlinks and cached pages. Needs pretty permalinks and a server that passes missing image requests to WordPress.',
						'pixelkiln-image-optimizer'
					) }
					checked={ bool( 'enable_404_fallback' ) }
					onChange={ ( v ) =>
						set( 'enable_404_fallback', v ? 1 : 0 )
					}
				/>
				<Select
					label={ __(
						'Keep original backups for',
						'pixelkiln-image-optimizer'
					) }
					help={ __(
						'After this, originals are deleted and images can no longer be restored.',
						'pixelkiln-image-optimizer'
					) }
					value={ String( values.backup_retention ?? 30 ) }
					options={ [
						{
							label: __( '7 days', 'pixelkiln-image-optimizer' ),
							value: '7',
						},
						{
							label: __( '30 days', 'pixelkiln-image-optimizer' ),
							value: '30',
						},
						{
							label: __( '90 days', 'pixelkiln-image-optimizer' ),
							value: '90',
						},
						{
							label: __(
								'Keep forever',
								'pixelkiln-image-optimizer'
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
						'pixelkiln-image-optimizer'
					) }
					help={ __(
						'The figures on the Bulk Optimize tab come from the last scan. A scan checks every image against the files on disk and runs in the background.',
						'pixelkiln-image-optimizer'
					) }
					value={ values.scan_frequency || 'weekly' }
					options={ [
						{
							label: __( 'Manual only', 'pixelkiln-image-optimizer' ),
							value: 'manual',
						},
						{
							label: __( 'Daily', 'pixelkiln-image-optimizer' ),
							value: 'daily',
						},
						{
							label: __(
								'Weekly (recommended)',
								'pixelkiln-image-optimizer'
							),
							value: 'weekly',
						},
						{
							label: __(
								'About every 30 days',
								'pixelkiln-image-optimizer'
							),
							value: 'monthly',
						},
					] }
					onChange={ ( v ) => set( 'scan_frequency', v ) }
				/>
			</Section>

			<Section
				icon={ <IconGear /> }
				title={ __( 'WordPress behaviour', 'pixelkiln-image-optimizer' ) }
				description={ __(
					'Core defaults this plugin can override.',
					'pixelkiln-image-optimizer'
				) }
			>
				<Toggle
					label={ __(
						'Disable WordPress automatic image scaling',
						'pixelkiln-image-optimizer'
					) }
					help={ __(
						'WordPress scales images over 2560px by default.',
						'pixelkiln-image-optimizer'
					) }
					checked={ bool( 'disable_wp_scaling' ) }
					onChange={ ( v ) => set( 'disable_wp_scaling', v ? 1 : 0 ) }
				/>
			</Section>

			<div className="pkio-savebar">
				<Button variant="primary" onClick={ save } disabled={ saving }>
					{ saving ? (
						<Spinner />
					) : (
						__( 'Save settings', 'pixelkiln-image-optimizer' )
					) }
				</Button>
			</div>
		</>
	);
};

export default SettingsPage;
