/**
 * Range slider with a live value readout.
 *
 * Replaces @wordpress/components RangeControl: label, help, value, onChange,
 * min, max, step. onChange receives a number, matching the original.
 */

import Field from './Field';

const Range = ( {
	label,
	help,
	value,
	onChange,
	min = 0,
	max = 100,
	step = 1,
} ) => (
	<Field label={ label } help={ help } className="pkio-range">
		{ ( { id, helpId } ) => (
			<div className="pkio-range__row">
				<input
					id={ id }
					type="range"
					className="pkio-range__input"
					value={ value }
					min={ min }
					max={ max }
					step={ step }
					aria-describedby={ helpId }
					onChange={ ( event ) =>
						onChange && onChange( Number( event.target.value ) )
					}
				/>
				<output className="pkio-range__value" htmlFor={ id }>
					{ value }
				</output>
			</div>
		) }
	</Field>
);

export default Range;
