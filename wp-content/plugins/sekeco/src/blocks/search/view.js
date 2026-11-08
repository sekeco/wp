/**
 * Full-screen search: native <dialog>, live REST results, random picks.
 */
const root = document.documentElement;

document.querySelectorAll( '[data-sk-search]' ).forEach( ( wrap ) => {
	const dialog = wrap.querySelector( 'dialog' );

	if ( ! dialog || ! dialog.showModal || ! wrap.dataset.endpoint ) {
		return;
	}

	const openers = wrap.querySelectorAll( '[data-sk-search-open]' );
	const input = wrap.querySelector( '.sk-search__input' );
	const list = wrap.querySelector( '[data-sk-search-results]' );
	const tiles = wrap.querySelector( '[data-sk-search-tiles]' );
	const label = wrap.querySelector( '[data-sk-search-label]' );
	const status = wrap.querySelector( '[data-sk-search-status]' );
	const endpoint = wrap.dataset.endpoint;
	const emptyLabel = wrap.dataset.emptyLabel;
	const recommendedLabel = label.textContent;

	// Controls only appear once the script can run them.
	openers.forEach( ( button ) => {
		button.hidden = false;
	} );

	let recommended = [];
	let picks = [];
	let loaded = false;
	let timer = 0;
	let controller = null;
	let active = -1;

	const el = ( tag, className, text ) => {
		const node = document.createElement( tag );

		if ( className ) {
			node.className = className;
		}

		if ( text ) {
			node.textContent = text;
		}

		return node;
	};

	const row = ( item ) => {
		const li = el( 'li', 'sk-search__item' );
		const a = el( 'a', 'sk-search__link' );
		const badge = el( 'span', 'sk-search__badge', item.label );
		const text = el( 'span', 'sk-search__text' );

		a.href = item.url;
		text.appendChild( el( 'span', 'sk-search__title', item.title ) );

		if ( item.detail ) {
			text.appendChild( el( 'span', 'sk-search__detail', item.detail ) );
		}

		a.append( badge, text );
		li.appendChild( a );

		return li;
	};

	const renderList = ( items ) => {
		list.replaceChildren( ...items.map( row ) );
		active = -1;
	};

	const renderTiles = () => {
		const frag = document.createDocumentFragment();

		picks.forEach( ( item, index ) => {
			const a = el( 'a', 'sk-search__tile' );
			const img = el( 'img' );
			const meta = el( 'span', 'sk-search__tile-meta' );

			a.href = item.url;
			a.style.setProperty( '--i', String( index ) );
			img.src = item.thumb;
			img.alt = '';
			img.loading = 'lazy';
			img.decoding = 'async';
			meta.append(
				el( 'span', 'sk-search__tile-type', item.label ),
				el( 'span', 'sk-search__tile-title', item.title )
			);
			a.append( img, meta );
			frag.appendChild( a );
		} );

		tiles.replaceChildren( frag );
	};

	const showRecommended = () => {
		label.textContent = recommendedLabel;
		status.textContent = '';
		renderList( recommended );
	};

	const load = async () => {
		if ( loaded ) {
			return;
		}

		loaded = true;

		try {
			const response = await fetch( `${ endpoint }suggest` );
			const data = await response.json();

			recommended = data.recommended || [];
			picks = data.tiles || [];
			renderTiles();

			if ( ! input.value.trim() ) {
				showRecommended();
			}
		} catch {
			loaded = false;
		}
	};

	const search = async ( query ) => {
		if ( controller ) {
			controller.abort();
		}

		controller = new window.AbortController();

		try {
			const response = await fetch(
				`${ endpoint }search?q=${ encodeURIComponent( query ) }`,
				{ signal: controller.signal }
			);
			const data = await response.json();
			const results = data.results || [];

			if ( input.value.trim() !== query ) {
				return;
			}

			if ( results.length ) {
				label.textContent = `${ results.length } ${
					1 === results.length ? 'result' : 'results'
				}`;
				status.textContent = '';
				renderList( results );
			} else {
				label.textContent = recommendedLabel;
				status.textContent = `${ emptyLabel } “${ query }”`;
				renderList( recommended );
			}
		} catch ( error ) {
			if ( 'AbortError' !== error.name ) {
				status.textContent = '';
			}
		}
	};

	const open = () => {
		if ( dialog.open ) {
			return;
		}

		dialog.showModal();
		root.classList.add( 'sk-search-open' );
		load();
		input.focus();
		input.select();
	};

	const close = () => dialog.close();

	dialog.addEventListener( 'close', () => {
		root.classList.remove( 'sk-search-open' );
	} );

	openers.forEach( ( button ) => button.addEventListener( 'click', open ) );
	wrap.querySelector( '[data-sk-search-close]' ).addEventListener(
		'click',
		close
	);

	// Esc closes even where the browser's own close request is withheld.
	dialog.addEventListener( 'keydown', ( event ) => {
		if ( 'Escape' === event.key ) {
			event.preventDefault();
			close();
		}
	} );

	// A click on the dialog itself (outside the panel content) closes it.
	dialog.addEventListener( 'click', ( event ) => {
		if ( event.target === dialog ) {
			close();
		}
	} );

	document.addEventListener( 'keydown', ( event ) => {
		const typing = /^(input|textarea|select)$/i.test(
			( event.target.tagName || '' ).toLowerCase()
		);

		if (
			( ( event.key === 'k' || event.key === 'K' ) &&
				( event.metaKey || event.ctrlKey ) ) ||
			( event.key === '/' && ! typing && ! dialog.open )
		) {
			event.preventDefault();
			open();
		}
	} );

	input.addEventListener( 'input', () => {
		window.clearTimeout( timer );

		const query = input.value.trim();

		if ( query.length < 2 ) {
			if ( controller ) {
				controller.abort();
			}

			showRecommended();
			return;
		}

		timer = window.setTimeout( () => search( query ), 180 );
	} );

	// Arrow keys move through the results; Enter opens the highlighted one.
	input.addEventListener( 'keydown', ( event ) => {
		const links = list.querySelectorAll( '.sk-search__link' );

		if ( ! links.length ) {
			return;
		}

		if ( 'ArrowDown' === event.key || 'ArrowUp' === event.key ) {
			event.preventDefault();
			active =
				'ArrowDown' === event.key
					? ( active + 1 ) % links.length
					: ( active - 1 + links.length ) % links.length;

			links.forEach( ( link, index ) =>
				link.classList.toggle( 'is-active', index === active )
			);
			links[ active ].scrollIntoView( { block: 'nearest' } );
		} else if ( 'Enter' === event.key && active > -1 ) {
			event.preventDefault();
			links[ active ].click();
		}
	} );
} );
