/** One icon + figure pair in the hero strip. */

const Metric = ( { icon, tone, value, label } ) => (
	<div className="oio-metric">
		<span className={ `oio-metric__icon is-${ tone }` }>{ icon }</span>
		<div className="oio-metric__value">{ value }</div>
		<div className="oio-metric__label">{ label }</div>
	</div>
);

export default Metric;
