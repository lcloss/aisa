/* Only successful CF7 submissions; never include field values in analytics. */
( function () {
	'use strict';

	document.addEventListener( 'wpcf7mailsent', function ( event ) {
		var target = event.target;
		if ( ! target || typeof target.querySelector !== 'function' ) {
			return;
		}
		var form = target.matches( 'form' ) ? target : target.querySelector( 'form' );
		if ( ! form ) {
			return;
		}
		var formId = [ 'form-voluntariado', 'form-contacto' ].find( function ( id ) {
			return form.dataset.formId === id || form.classList.contains( id );
		} );
		if ( ! formId ) {
			return;
		}
		window.dataLayer = window.dataLayer || [];
		window.dataLayer.push( { event: 'form_submit', form_id: formId } );
	} );
}() );
