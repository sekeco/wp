/**
 * Category filter for the Project grid.
 */
document.querySelectorAll( '[data-sk-filter]' ).forEach( ( grid ) => {
	const buttons = grid.querySelectorAll( '.sk-filter__btn' );
	const items = grid.querySelectorAll( '.sk-projects__item' );

	buttons.forEach( ( button ) => {
		button.addEventListener( 'click', () => {
			const key = button.dataset.filter;

			buttons.forEach( ( other ) => {
				const active = other === button;

				other.classList.toggle( 'is-active', active );
				other.setAttribute( 'aria-pressed', String( active ) );
			} );

			items.forEach( ( item ) => {
				const show =
					'*' === key ||
					( item.dataset.terms || '' ).split( ' ' ).includes( key );

				item.hidden = ! show;
			} );
		} );
	} );
} );
