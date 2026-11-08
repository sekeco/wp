/**
 * Filterable Query frontend runtime.
 */

const initFilter = ( wrapper ) => {
	if ( 'true' === wrapper.dataset.sekecoFilterReady ) {
		return;
	}

	wrapper.dataset.sekecoFilterReady = 'true';

	const filters = wrapper.querySelector(
		'.sekeco-filterable-query__filters'
	);
	const results = wrapper.querySelector(
		'.sekeco-filterable-query__results'
	);

	if ( ! filters || ! results ) {
		return;
	}

	const buttons = Array.from(
		filters.querySelectorAll( '[data-sekeco-filter-term]' )
	);

	if ( ! buttons.length ) {
		return;
	}

	const items = Array.from(
		results.querySelectorAll( '.wp-block-post-template > li' )
	);

	if ( ! items.length ) {
		return;
	}

	const empty = wrapper.querySelector( '.sekeco-filterable-query__empty' );

	items.forEach( ( item ) => {
		const terms = Array.from(
			item.querySelectorAll( '[data-sekeco-term-slug]' )
		)
			.map( ( link ) => link.getAttribute( 'data-sekeco-term-slug' ) )
			.filter( Boolean );

		item.dataset.sekecoTerms = Array.from( new Set( terms ) ).join( ' ' );
	} );

	const apply = ( term ) => {
		let visible = 0;

		items.forEach( ( item ) => {
			const itemTerms = ( item.dataset.sekecoTerms || '' )
				.split( ' ' )
				.filter( Boolean );
			const show = 'all' === term || itemTerms.includes( term );

			item.hidden = ! show;

			if ( show ) {
				visible += 1;
			}
		} );

		buttons.forEach( ( button ) => {
			const active = button.dataset.sekecoFilterTerm === term;

			button.classList.toggle( 'is-active', active );
			button.setAttribute( 'aria-pressed', active ? 'true' : 'false' );
		} );

		if ( empty ) {
			empty.hidden = visible > 0;
		}
	};

	buttons.forEach( ( button ) => {
		button.addEventListener( 'click', () =>
			apply( button.dataset.sekecoFilterTerm )
		);
	} );

	wrapper.classList.add( 'sekeco-filterable-query--js' );

	const initial =
		buttons.find( ( button ) =>
			button.classList.contains( 'is-active' )
		) || buttons[ 0 ];

	apply( initial.dataset.sekecoFilterTerm );
};

const initAll = () => {
	document
		.querySelectorAll( '.sekeco-filterable-query[data-sekeco-filterable]' )
		.forEach( initFilter );
};

if ( 'loading' === document.readyState ) {
	document.addEventListener( 'DOMContentLoaded', initAll );
} else {
	initAll();
}
