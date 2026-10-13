/** One icon + figure pair in the hero strip. */

const Metric = ( { icon, tone, value, label } ) => (
	<div className="pkio-metric">
		<span className={ `pkio-metric__icon is-${ tone }` }>{ icon }</span>
		<div className="pkio-metric__value">{ value }</div>
		<div className="pkio-metric__label">{ label }</div>
	</div>
);

export default Metric;
