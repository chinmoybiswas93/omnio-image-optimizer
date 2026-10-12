/** A titled settings block. */

import { Card, CardBody, CardHeader } from './Card';

const Section = ( { icon, title, description, children } ) => (
	<Card className="oio-section">
		<CardHeader className="oio-section__header">
			<span className="oio-section__icon">{ icon }</span>
			<span className="oio-section__heading">
				<span className="oio-section__title">{ title }</span>
				{ description && (
					<span className="oio-section__desc">{ description }</span>
				) }
			</span>
		</CardHeader>
		<CardBody>{ children }</CardBody>
	</Card>
);

export default Section;
