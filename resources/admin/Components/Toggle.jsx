/**
 * Toggle switch.
 *
 * Replaces @wordpress/components ToggleControl: label, help, checked,
 * onChange, disabled. onChange receives the boolean, matching the original.
 *
 * Built on a real checkbox rather than a div with a click handler, so it is
 * focusable, space-togglable and announced correctly without extra ARIA.
 */

import useFieldId from './useFieldId';

const Toggle = ( {
	label,
	help,
	checked = false,
	onChange,
	disabled = false,
} ) => {
	const id = useFieldId( 'pkio-toggle' );
	const helpId = help ? `${ id }-help` : undefined;

	return (
		<div
			className={ `pkio-field pkio-toggle${
				disabled ? ' is-disabled' : ''
			}` }
		>
			<label className="pkio-toggle__row" htmlFor={ id }>
				<input
					id={ id }
					type="checkbox"
					className="pkio-toggle__input"
					checked={ !! checked }
					disabled={ disabled }
					aria-describedby={ helpId }
					onChange={ ( event ) =>
						onChange && onChange( event.target.checked )
					}
				/>
				<span className="pkio-toggle__track" aria-hidden="true">
					<span className="pkio-toggle__thumb" />
				</span>
				<span className="pkio-toggle__label">{ label }</span>
			</label>
			{ help && (
				<p className="pkio-field__help" id={ helpId }>
					{ help }
				</p>
			) }
		</div>
	);
};

export default Toggle;
