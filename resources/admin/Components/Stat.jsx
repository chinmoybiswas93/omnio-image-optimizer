/** A single headline figure. */

const Stat = ( { label, value, tone } ) => (
	<div className={ `oio-stat${ tone ? ` is-${ tone }` : '' }` }>
		<div className="oio-stat__value">{ value }</div>
		<div className="oio-stat__label">{ label }</div>
	</div>
);

export default Stat;
