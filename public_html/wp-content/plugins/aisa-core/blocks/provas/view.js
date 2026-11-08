/**
 * Provas — contagem animada dos números quando a faixa entra no ecrã.
 * O HTML traz sempre o valor final (sem JS / leitores de ecrã / SEO).
 * Preserva prefixo/sufixo ("+30", "95%") e separador de milhares ("1.200").
 */
( function () {
	var nums = document.querySelectorAll( '.wp-block-aisa-provas .proof__num' );
	if ( ! nums.length ) { return; }
	if ( ! ( 'IntersectionObserver' in window ) ) { return; }
	if ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) { return; }

	var DURATION = 1600;
	var items = [];

	Array.prototype.forEach.call( nums, function ( el ) {
		var text = el.textContent.trim();
		var m = text.match( /^(\D*?)(\d{1,3}(?:[.\s ]\d{3})+|\d+)(.*)$/ );
		if ( ! m ) { return; }
		var sep = ( m[2].match( /[.\s ]/ ) || [ '' ] )[0];
		var target = parseInt( m[2].replace( /\D/g, '' ), 10 );
		if ( ! target ) { return; }
		var item = { el: el, prefix: m[1], suffix: m[3], target: target, sep: sep, final: text };
		el.setAttribute( 'aria-label', text );
		el.style.fontVariantNumeric = 'tabular-nums';
		render( item, 0 );
		items.push( item );
	} );

	function format( n, sep ) {
		var s = String( n );
		return sep ? s.replace( /\B(?=(\d{3})+(?!\d))/g, sep ) : s;
	}

	function render( item, n ) {
		item.el.textContent = item.prefix + format( n, item.sep ) + item.suffix;
	}

	function run( item ) {
		var start = null;
		function step( ts ) {
			if ( start === null ) { start = ts; }
			var p = Math.min( ( ts - start ) / DURATION, 1 );
			var eased = 1 - Math.pow( 1 - p, 3 ); // easeOutCubic
			if ( p < 1 ) {
				render( item, Math.round( item.target * eased ) );
				requestAnimationFrame( step );
			} else {
				item.el.textContent = item.final;
			}
		}
		requestAnimationFrame( step );
	}

	var io = new IntersectionObserver( function ( entries ) {
		entries.forEach( function ( entry ) {
			if ( ! entry.isIntersecting ) { return; }
			io.unobserve( entry.target );
			items.forEach( function ( item ) {
				if ( entry.target.contains( item.el ) ) { run( item ); }
			} );
		} );
	}, { threshold: 0.35 } );

	Array.prototype.forEach.call( document.querySelectorAll( '.wp-block-aisa-provas' ), function ( block ) {
		io.observe( block );
	} );
}() );
