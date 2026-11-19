(function (wp) {
 'use strict';
 var el = wp.element.createElement, __ = wp.i18n.__;
 wp.blocks.registerBlockType('aisa/breadcrumbs', {
  apiVersion: 3, title: __('Breadcrumbs', 'aisa'), category: 'aisa', icon: 'arrow-right-alt2',
  edit: function () {
   return el('p', wp.blockEditor.useBlockProps({ className: 'breadcrumb' }), __('Início › … › página actual', 'aisa'));
  },
  save: function () { return null; }
 });
}(window.wp));
