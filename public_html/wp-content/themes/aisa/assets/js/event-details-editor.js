(function (blocks, element, i18n, serverSideRender) {
	'use strict';
	blocks.registerBlockType('aisa/event-details', {
		apiVersion: 3,
		title: i18n.__('Data e local do evento', 'aisa'),
		category: 'aisa',
		icon: 'calendar-alt',
		usesContext: ['postId', 'postType'],
		supports: { html: false, inserter: false },
		edit: function (props) {
			return element.createElement(serverSideRender.default || serverSideRender, {
				block: 'aisa/event-details', attributes: props.attributes,
				urlQueryArgs: { post_id: props.context.postId }
			});
		},
		save: function () { return null; }
	});
})(window.wp.blocks, window.wp.element, window.wp.i18n, window.wp.serverSideRender);
