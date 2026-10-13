/** A single headline figure. */

const Stat = ( { label, value, tone } ) => (
	<div className={ `pkio-stat${ tone ? ` is-${ tone }` : '' }` }>
		<div className="pkio-stat__value">{ value }</div>
		<div className="pkio-stat__label">{ label }</div>
	</div>
);

export default Stat;
