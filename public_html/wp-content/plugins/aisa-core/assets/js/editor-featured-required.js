/* global wp */
( function () {
	'use strict';
	wp.domReady( function () {
		var locked = null;
		function check() {
			var editor = wp.data.select( 'core/editor' );
			if ( ! editor || editor.getCurrentPostType() !== 'post' ) { return; }
			var missing = ! editor.getEditedPostAttribute( 'featured_media' );
			if ( missing === locked ) { return; }
			locked = missing;
			if ( missing ) {
				wp.data.dispatch( 'core/editor' ).lockPostSaving( 'aisa-featured' );
				wp.data.dispatch( 'core/notices' ).createWarningNotice( wp.i18n.__( 'A notícia precisa de uma imagem destacada (com texto alternativo)', 'aisa' ), { id: 'aisa-featured', isDismissible: false } );
			} else {
				wp.data.dispatch( 'core/editor' ).unlockPostSaving( 'aisa-featured' );
				wp.data.dispatch( 'core/notices' ).removeNotice( 'aisa-featured' );
			}
		}
		wp.data.subscribe( check );
		check();
	} );
}() );
