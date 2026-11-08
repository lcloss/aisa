/* Keep CF7's live announcements and move focus to the first invalid control. */
( function () {
	'use strict';
	document.addEventListener( 'wpcf7invalid', function ( event ) {
		var form = event.target.matches( '.form-voluntariado, .form-contacto' ) ? event.target : event.target.querySelector( '.form-voluntariado, .form-contacto' );
		if ( ! form ) {
			return;
		}
		// CF7 emits this event before finishing its own response/focus updates.
		window.requestAnimationFrame( function () {
			var field = form.querySelector( 'input[aria-invalid="true"], textarea[aria-invalid="true"], select[aria-invalid="true"], [aria-invalid="true"] input' );
			if ( field ) {
				field.focus();
			}
		} );
	} );
}() );
