/** One diagnostics row: label, value and, when something is wrong, the remedy. */

const DiagnosticRow = ( { row } ) => (
	<div className={ `oio-diagnostics__row is-${ row.state }` }>
		<span className="oio-diagnostics__label">{ row.label }</span>
		<span className="oio-diagnostics__value">
			{ row.value }
			{ row.hint && (
				<span className="oio-diagnostics__hint">{ row.hint }</span>
			) }
		</span>
		<span
			className={ `oio-diagnostics__state is-${ row.state }` }
			aria-hidden="true"
		/>
	</div>
);

export default DiagnosticRow;
