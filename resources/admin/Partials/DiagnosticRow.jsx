/** One diagnostics row: label, value and, when something is wrong, the remedy. */

const DiagnosticRow = ( { row } ) => (
	<div className={ `pkio-diagnostics__row is-${ row.state }` }>
		<span className="pkio-diagnostics__label">{ row.label }</span>
		<span className="pkio-diagnostics__value">
			{ row.value }
			{ row.hint && (
				<span className="pkio-diagnostics__hint">{ row.hint }</span>
			) }
		</span>
		<span
			className={ `pkio-diagnostics__state is-${ row.state }` }
			aria-hidden="true"
		/>
	</div>
);

export default DiagnosticRow;
