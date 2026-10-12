/**
 * Tab navigation.
 *
 * Replaces core's TabPanel: its active indicator is an animated pseudo-element
 * pinned to the item's bottom edge, which fought every attempt to sit flush
 * with the rule beneath it. Here the indicator is the tab's own bottom border,
 * so there is nothing to align and nothing to animate out of position.
 *
 * Implements the WAI-ARIA tabs pattern: roving tabindex, arrow/Home/End keys.
 */

import { useState, useRef } from '@wordpress/element';

const Tabs = ( { tabs, initial, children } ) => {
	const [ active, setActive ] = useState( initial || tabs[ 0 ].name );
	const refs = useRef( {} );

	const select = ( tab ) => {
		setActive( tab.name );
		refs.current[ tab.name ]?.focus();
	};

	const onKeyDown = ( event ) => {
		const index = tabs.findIndex( ( t ) => t.name === active );
		const last = tabs.length - 1;
		let next;

		switch ( event.key ) {
			case 'ArrowRight':
				next = tabs[ index === last ? 0 : index + 1 ];
				break;
			case 'ArrowLeft':
				next = tabs[ index === 0 ? last : index - 1 ];
				break;
			case 'Home':
				next = tabs[ 0 ];
				break;
			case 'End':
				next = tabs[ last ];
				break;
			default:
				return;
		}

		event.preventDefault();
		select( next );
	};

	const current = tabs.find( ( t ) => t.name === active ) || tabs[ 0 ];

	return (
		<div className="oio-tabs">
			{ /* eslint-disable-next-line jsx-a11y/no-noninteractive-element-to-interactive-role */ }
			<div
				className="oio-tabs__list"
				role="tablist"
				onKeyDown={ onKeyDown }
			>
				{ tabs.map( ( tab ) => {
					const isActive = tab.name === active;

					return (
						<button
							key={ tab.name }
							ref={ ( el ) => ( refs.current[ tab.name ] = el ) }
							type="button"
							role="tab"
							id={ `oio-tab-${ tab.name }` }
							aria-controls={ `oio-tabpanel-${ tab.name }` }
							aria-selected={ isActive }
							tabIndex={ isActive ? 0 : -1 }
							// Reserves the bold width so activating a tab cannot reflow the row.
							data-title={ tab.title }
							className={ `oio-tabs__tab${
								isActive ? ' is-active' : ''
							}` }
							onClick={ () => setActive( tab.name ) }
						>
							{ tab.title }
						</button>
					);
				} ) }
			</div>

			<div
				className="oio-tabs__panel"
				role="tabpanel"
				id={ `oio-tabpanel-${ current.name }` }
				aria-labelledby={ `oio-tab-${ current.name }` }
				tabIndex={ 0 }
			>
				{ children( current ) }
			</div>
		</div>
	);
};

export default Tabs;
