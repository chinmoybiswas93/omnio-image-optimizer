/** A titled settings block. */

import { Card, CardBody, CardHeader } from './Card';

const Section = ( { icon, title, description, children } ) => (
	<Card className="pkio-section">
		<CardHeader className="pkio-section__header">
			<span className="pkio-section__icon">{ icon }</span>
			<span className="pkio-section__heading">
				<span className="pkio-section__title">{ title }</span>
				{ description && (
					<span className="pkio-section__desc">{ description }</span>
				) }
			</span>
		</CardHeader>
		<CardBody>{ children }</CardBody>
	</Card>
);

export default Section;
