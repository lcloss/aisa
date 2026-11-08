/* Cookiebot owns the banner, consent storage and Consent Mode updates. */
(function () {
	'use strict';
	document.addEventListener('click', function (event) {
		var link = event.target.closest('.aisa-cookie-prefs');
		if (!link || !window.Cookiebot || typeof window.Cookiebot.renew !== 'function') {
			return; // The policy remains a usable destination if the CMP cannot load.
		}
		event.preventDefault();
		window.Cookiebot.renew();
	});
}());
