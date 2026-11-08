/**
 * Frontend behaviour for Sekeco forms and surveys.
 */

const EMAIL_RE = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;

const fieldsByName = ( form, name ) => {
	const direct = Array.from( form.querySelectorAll( `[name="${ name }"]` ) );

	if ( direct.length ) {
		return direct;
	}

	return Array.from( form.querySelectorAll( `[name="${ name }[]"]` ) );
};

const wrapperFor = ( input ) => input.closest( '.sekeco-form__field' );

const setError = ( form, name, message ) => {
	const inputs = fieldsByName( form, name );

	if ( ! inputs.length ) {
		return;
	}

	const wrapper = wrapperFor( inputs[ 0 ] );
	const error = wrapper
		? wrapper.querySelector( '.sekeco-form__error' )
		: null;

	inputs.forEach( ( input ) => input.setAttribute( 'aria-invalid', 'true' ) );

	if ( error ) {
		error.textContent = message;
		error.hidden = false;
	}
};

const clearErrors = ( form ) => {
	form.querySelectorAll( '.sekeco-form__error' ).forEach( ( error ) => {
		error.textContent = '';
		error.hidden = true;
	} );

	form.querySelectorAll( '[aria-invalid="true"]' ).forEach( ( input ) => {
		input.removeAttribute( 'aria-invalid' );
	} );
};

const validateWithin = ( root ) => {
	const errors = {};

	root.querySelectorAll( '[required], [data-required-group]' ).forEach(
		( input ) => {
			if ( errors[ input.name ] ) {
				return;
			}

			if ( input.name.endsWith( '[]' ) ) {
				const checked = fieldsByName(
					root.closest( 'form' ),
					input.name
				).some( ( box ) => box.checked );

				if ( ! checked ) {
					errors[ input.name ] = 'Choose at least one option.';
				}

				return;
			}

			if ( input.type === 'radio' ) {
				const checked = fieldsByName(
					root.closest( 'form' ),
					input.name
				).some( ( radio ) => radio.checked );

				if ( ! checked ) {
					errors[ input.name ] = 'Choose one option.';
				}

				return;
			}

			if ( input.type === 'checkbox' ) {
				if ( ! input.checked ) {
					errors[ input.name ] = 'This field is required.';
				}

				return;
			}

			const value = input.value.trim();

			if ( ! value ) {
				errors[ input.name ] = 'This field is required.';
			} else if ( input.type === 'email' && ! EMAIL_RE.test( value ) ) {
				errors[ input.name ] = 'Enter a valid email address.';
			}
		}
	);

	return errors;
};

const showErrors = ( form, errors ) => {
	Object.entries( errors ).forEach( ( [ name, message ] ) =>
		setError( form, name, message )
	);

	const first = form.querySelector( '[aria-invalid="true"]' );

	if ( first ) {
		first.focus( { preventScroll: false } );
	}
};

const setStatus = ( form, message ) => {
	const status = form.querySelector( '.sekeco-form__status' );

	if ( status ) {
		status.textContent = message;
	}
};

const showSuccess = ( form, message ) => {
	const success = document.createElement( 'p' );
	success.className = 'sekeco-form__success-message';
	success.textContent = message;
	success.setAttribute( 'role', 'status' );
	form.replaceChildren( success );
};

const updateProgress = ( form ) => {
	if ( form.classList.contains( 'sekeco-form--stepped' ) ) {
		return;
	}

	const bar = form.querySelector( '.sekeco-form__progress-bar' );

	if ( ! bar ) {
		return;
	}

	const questions = Array.from(
		form.querySelectorAll( '.sekeco-form__field' )
	);

	if ( ! questions.length ) {
		return;
	}

	const answered = questions.filter( ( question ) =>
		Array.from(
			question.querySelectorAll( 'input, textarea, select' )
		).some( ( input ) => {
			if ( input.type === 'checkbox' || input.type === 'radio' ) {
				return input.checked;
			}

			return '' !== input.value.trim();
		} )
	).length;

	bar.style.width = `${ Math.round( ( answered / questions.length ) * 100 ) }%`;
};

const pad = ( value ) => String( value ).padStart( 2, '0' );

const setupStepped = ( form ) => {
	const steps = Array.from( form.querySelectorAll( '.sekeco-form__step' ) );

	if ( steps.length < 2 ) {
		return;
	}

	const next = form.querySelector( '.sekeco-form__next' );
	const back = form.querySelector( '.sekeco-form__back' );
	const submit = form.querySelector( '.sekeco-form__submit' );
	const counter = form.querySelector( '.sekeco-form__counter' );
	const bar = form.querySelector( '.sekeco-form__progress-bar' );

	let index = 0;

	form.classList.add( 'sekeco-form--stepped' );

	const render = ( direction ) => {
		steps.forEach( ( step, position ) => {
			const active = position === index;

			step.classList.toggle( 'is-active', active );
			step.classList.toggle( 'is-back', active && 'back' === direction );
			step.setAttribute( 'aria-hidden', active ? 'false' : 'true' );
		} );

		if ( counter ) {
			counter.textContent = `${ pad( index + 1 ) } / ${ pad(
				steps.length
			) }`;
		}

		if ( bar ) {
			bar.style.width = `${ Math.round(
				( ( index + 1 ) / steps.length ) * 100
			) }%`;
		}

		if ( back ) {
			back.hidden = index === 0;
		}

		if ( next ) {
			next.hidden = index === steps.length - 1;
		}

		if ( submit ) {
			submit.hidden = index !== steps.length - 1;
		}
	};

	const go = ( delta ) => {
		if ( delta > 0 ) {
			const errors = validateWithin( steps[ index ] );

			if ( Object.keys( errors ).length ) {
				showErrors( form, errors );
				return;
			}
		}

		index = Math.min( steps.length - 1, Math.max( 0, index + delta ) );
		clearErrors( form );
		render( delta < 0 ? 'back' : 'forward' );

		const first = steps[ index ].querySelector( 'input, textarea, select' );

		if ( first ) {
			first.focus( { preventScroll: true } );
		}
	};

	if ( next ) {
		next.addEventListener( 'click', () => go( 1 ) );
	}

	if ( back ) {
		back.addEventListener( 'click', () => go( -1 ) );
	}

	form.addEventListener( 'keydown', ( event ) => {
		if ( 'Enter' !== event.key || event.shiftKey ) {
			return;
		}

		if ( event.target.matches( 'textarea, button' ) ) {
			return;
		}

		event.preventDefault();

		if ( index < steps.length - 1 ) {
			go( 1 );
		} else if ( submit ) {
			form.requestSubmit();
		}
	} );

	render( 'forward' );
};

const enhance = ( form ) => {
	if ( form.dataset.sekecoFormReady === 'true' ) {
		return;
	}

	form.dataset.sekecoFormReady = 'true';
	form.classList.add( 'sekeco-form--js' );

	form.addEventListener( 'submit', async ( event ) => {
		event.preventDefault();

		clearErrors( form );

		const errors = validateWithin( form );

		if ( Object.keys( errors ).length ) {
			showErrors( form, errors );
			return;
		}

		const button = form.querySelector( '.sekeco-form__submit' );
		const rest = form.dataset.rest;

		if ( button ) {
			button.disabled = true;
		}

		form.classList.add( 'is-busy' );

		try {
			const response = await fetch( rest, {
				method: 'POST',
				body: new FormData( form ),
				headers: { Accept: 'application/json' },
			} );

			const payload = await response.json().catch( () => ( {} ) );

			if ( ! response.ok ) {
				const fieldErrors = payload?.data?.errors || {};

				if ( fieldErrors && Object.keys( fieldErrors ).length ) {
					showErrors( form, fieldErrors );
				} else {
					setStatus(
						form,
						payload.message ||
							'Something went wrong. Please try again.'
					);
				}

				return;
			}

			showSuccess(
				form,
				payload.message || form.dataset.success || 'Thanks!'
			);

			if ( form.dataset.redirect ) {
				window.setTimeout( () => {
					window.location.assign( form.dataset.redirect );
				}, 700 );
			}
		} catch {
			setStatus(
				form,
				'Network error. Please check your connection and try again.'
			);
		} finally {
			if ( button ) {
				button.disabled = false;
			}

			form.classList.remove( 'is-busy' );
		}
	} );

	form.addEventListener( 'input', () => updateProgress( form ) );
	form.addEventListener( 'change', () => updateProgress( form ) );

	form.addEventListener( 'input', ( event ) => {
		const input = event.target;

		if ( ! input.name || 'true' !== input.getAttribute( 'aria-invalid' ) ) {
			return;
		}

		const wrapper = input.closest( '.sekeco-form__field' );
		const error = wrapper
			? wrapper.querySelector( '.sekeco-form__error' )
			: null;

		if ( error ) {
			error.textContent = '';
			error.hidden = true;
		}

		fieldsByName( form, input.name ).forEach( ( field ) =>
			field.removeAttribute( 'aria-invalid' )
		);
	} );

	if ( form.hasAttribute( 'data-sekeco-survey' ) ) {
		setupStepped( form );
	}

	updateProgress( form );
};

const init = () => {
	document
		.querySelectorAll( '[data-sekeco-form]' )
		.forEach( ( form ) => enhance( form ) );
};

if ( 'loading' === document.readyState ) {
	document.addEventListener( 'DOMContentLoaded', init );
} else {
	init();
}
