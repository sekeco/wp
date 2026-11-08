/**
 * Google Forms-style field builder for the Sekeco form editor.
 */
( function () {
	const body = document.getElementById( 'sekeco-fields-body' );
	const template = document.getElementById( 'sekeco-field-template' );
	const add = document.getElementById( 'sekeco-add-field' );

	if ( ! body || ! template || ! add ) {
		return;
	}

	const typePicker = document.getElementById( 'sekeco-add-type' );
	const postForm = document.getElementById( 'post' );

	const OPTION_TYPES = [ 'select', 'radio', 'checkbox_group' ];
	let index = body.querySelectorAll( '.sekeco-field' ).length;

	const reindex = () => {
		body.querySelectorAll( '.sekeco-field' ).forEach(
			( card, position ) => {
				card.dataset.index = String( position );

				card.querySelectorAll( '[name^="sekeco_fields"]' ).forEach(
					( input ) => {
						input.name = input.name.replace(
							/^sekeco_fields\[[^\]]*\]/,
							`sekeco_fields[${ position }]`
						);
					}
				);
			}
		);

		index = body.querySelectorAll( '.sekeco-field' ).length;
	};

	const storeOf = ( card ) => card.querySelector( '.sekeco-field__options' );

	const syncOptions = ( card ) => {
		const store = storeOf( card );
		const list = card.querySelector( '.sekeco-options' );

		if ( ! store || ! list ) {
			return;
		}

		store.value = Array.from(
			list.querySelectorAll( '.sekeco-option-input' )
		)
			.map( ( input ) => input.value.trim() )
			.filter( Boolean )
			.join( '\n' );
	};

	const optionRow = ( value ) => {
		const row = document.createElement( 'div' );
		row.className = 'sekeco-option';

		const input = document.createElement( 'input' );
		input.type = 'text';
		input.className = 'sekeco-option-input';
		input.value = value;
		input.placeholder = 'Option';

		const remove = document.createElement( 'button' );
		remove.type = 'button';
		remove.className = 'button-link sekeco-option-remove';
		remove.setAttribute( 'aria-label', 'Remove option' );
		remove.textContent = '×';

		row.append( input, remove );

		return row;
	};

	const buildOptions = ( card ) => {
		const store = storeOf( card );
		const list = card.querySelector( '.sekeco-options' );

		if ( ! store || ! list ) {
			return;
		}

		list.textContent = '';

		const values = store.value
			.split( /[\r\n,]+/ )
			.map( ( value ) => value.trim() )
			.filter( Boolean );

		( values.length ? values : [ '' ] ).forEach( ( value ) => {
			list.appendChild( optionRow( value ) );
		} );
	};

	const toggleOptions = ( card ) => {
		const type = card.querySelector( '.sekeco-field__type' );
		const wrap = card.querySelector( '.sekeco-field__options-wrap' );

		if ( ! type || ! wrap ) {
			return;
		}

		const uses = OPTION_TYPES.includes( type.value );
		wrap.hidden = ! uses;

		if ( uses && ! wrap.querySelector( '.sekeco-option' ) ) {
			buildOptions( card );
		}
	};

	const addCard = ( type ) => {
		const html = template.innerHTML.replace( /__INDEX__/g, index );
		body.insertAdjacentHTML( 'beforeend', html );
		index += 1;

		const cards = body.querySelectorAll( '.sekeco-field' );
		const card = cards[ cards.length - 1 ];
		const typeField = card.querySelector( '.sekeco-field__type' );

		if ( typeField ) {
			typeField.value = type;
		}

		buildOptions( card );
		toggleOptions( card );

		const label = card.querySelector( '.sekeco-field__label' );

		if ( label ) {
			label.focus();
		}
	};

	add.addEventListener( 'click', () => {
		addCard( typePicker ? typePicker.value : 'text' );
	} );

	body.addEventListener( 'click', ( event ) => {
		const card = event.target.closest( '.sekeco-field' );

		if ( ! card ) {
			return;
		}

		if ( event.target.closest( '.sekeco-option-add' ) ) {
			const list = card.querySelector( '.sekeco-options' );
			const row = optionRow( '' );
			list.appendChild( row );
			row.querySelector( 'input' ).focus();
			return;
		}

		if ( event.target.closest( '.sekeco-option-remove' ) ) {
			const row = event.target.closest( '.sekeco-option' );
			const list = card.querySelector( '.sekeco-options' );

			if ( row ) {
				row.remove();
			}

			if ( list && ! list.querySelector( '.sekeco-option' ) ) {
				list.appendChild( optionRow( '' ) );
			}

			syncOptions( card );
			return;
		}

		if ( event.target.closest( '.sekeco-remove-field' ) ) {
			card.remove();
			reindex();

			if ( ! body.querySelector( '.sekeco-field' ) ) {
				addCard( 'text' );
			}

			return;
		}

		if ( event.target.closest( '.sekeco-duplicate' ) ) {
			const clone = card.cloneNode( true );
			card.after( clone );
			reindex();
			buildOptions( clone );
			toggleOptions( clone );
			return;
		}

		if ( event.target.closest( '.sekeco-move-up' ) ) {
			const previous = card.previousElementSibling;

			if ( previous ) {
				card.parentNode.insertBefore( card, previous );
				reindex();
			}

			return;
		}

		if ( event.target.closest( '.sekeco-move-down' ) ) {
			const next = card.nextElementSibling;

			if ( next ) {
				card.parentNode.insertBefore( next, card );
				reindex();
			}
		}
	} );

	body.addEventListener( 'input', ( event ) => {
		const card = event.target.closest( '.sekeco-field' );

		if (
			card &&
			event.target.classList.contains( 'sekeco-option-input' )
		) {
			syncOptions( card );
		}
	} );

	body.addEventListener( 'change', ( event ) => {
		const card = event.target.closest( '.sekeco-field' );

		if ( ! card ) {
			return;
		}

		if ( event.target.classList.contains( 'sekeco-field__type' ) ) {
			toggleOptions( card );
			syncOptions( card );
		}
	} );

	let dragged = null;

	body.addEventListener( 'mousedown', ( event ) => {
		const handle = event.target.closest( '.sekeco-field__handle' );

		if ( handle ) {
			handle.closest( '.sekeco-field' ).draggable = true;
		}
	} );

	document.addEventListener( 'mouseup', () => {
		body.querySelectorAll( '.sekeco-field' ).forEach( ( card ) => {
			card.draggable = false;
		} );
	} );

	body.addEventListener( 'dragstart', ( event ) => {
		const card = event.target.closest( '.sekeco-field' );

		if ( ! card || ! card.draggable ) {
			return;
		}

		dragged = card;
		card.classList.add( 'is-dragging' );
		event.dataTransfer.effectAllowed = 'move';
		event.dataTransfer.setData( 'text/plain', card.dataset.index || '' );
	} );

	body.addEventListener( 'dragend', () => {
		if ( dragged ) {
			dragged.classList.remove( 'is-dragging' );
		}

		dragged = null;
		reindex();
	} );

	body.addEventListener( 'dragover', ( event ) => {
		if ( ! dragged ) {
			return;
		}

		event.preventDefault();

		const target = event.target.closest( '.sekeco-field' );

		if ( ! target || target === dragged ) {
			return;
		}

		const rect = target.getBoundingClientRect();
		const after = event.clientY - rect.top > rect.height / 2;

		if ( after ) {
			target.after( dragged );
		} else {
			target.before( dragged );
		}
	} );

	if ( postForm ) {
		postForm.addEventListener( 'submit', () => {
			body.querySelectorAll( '.sekeco-field' ).forEach( syncOptions );
		} );
	}

	body.querySelectorAll( '.sekeco-field' ).forEach( ( card ) => {
		buildOptions( card );
		toggleOptions( card );
	} );
} )();
