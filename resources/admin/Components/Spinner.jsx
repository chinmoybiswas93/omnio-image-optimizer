/**
 * Spinner.
 *
 * Pure CSS, so it costs no markup beyond one element and inherits currentColor
 * from whatever it sits inside.
 */

const Spinner = ( { className = '' } ) => (
	<span
		className={ [ 'pkio-spinner', className ].filter( Boolean ).join( ' ' ) }
		role="status"
		aria-hidden="true"
	/>
);

export default Spinner;
