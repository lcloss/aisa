/* Enhance native navigation blocks without dependencies. */
(() => {
 'use strict';
 const panel = document.getElementById('aisa-menu-panel');
 const trigger = document.querySelector('.site-header__menu');
 if (!panel || !trigger) return;
 const mobile = window.matchMedia('(max-width: 1100px)');
 const closeButton = panel.querySelector('.aisa-menu-close');
 const nav = panel.querySelector('.wp-block-navigation');
 const toggles = [...nav.querySelectorAll('button.wp-block-navigation-item__content')];
 const background = [];
 let previousOverflow = '';
 const setExpanded = (button, expanded) => {
  button.setAttribute('aria-expanded', String(expanded));
  const list = button.parentElement.querySelector(':scope > .wp-block-navigation__submenu-container');
  if (list) list.hidden = !expanded;
 };
 toggles.forEach((button, index) => {
  const list = button.parentElement.querySelector(':scope > .wp-block-navigation__submenu-container');
  if (!list) return;
  list.id = `aisa-submenu-${index}`;
  button.setAttribute('aria-controls', list.id);
  setExpanded(button, !mobile.matches && button.parentElement.classList.contains('aisa-nav-group'));
 });
 const visibleControls = () => [...panel.querySelectorAll('a[href],button,input,[tabindex="0"]')].filter(el => el.getClientRects().length && !el.closest('[hidden]') && !el.disabled);
 function closeMenu(restore = true) {
  if (!panel.classList.contains('is-open')) return;
  panel.classList.remove('is-open');
  panel.removeAttribute('role'); panel.removeAttribute('aria-modal'); panel.removeAttribute('aria-label');
  trigger.setAttribute('aria-expanded', 'false');
  document.body.classList.remove('aisa-menu-open');
  document.body.style.overflow = previousOverflow;
  background.forEach(([el, inert]) => { el.inert = inert; }); background.length = 0;
  if (restore) trigger.focus();
 }
 function openMenu() {
  panel.classList.add('is-open');
  panel.setAttribute('role','dialog'); panel.setAttribute('aria-modal','true');
  panel.setAttribute('aria-label', nav.getAttribute('aria-label') || trigger.textContent.trim());
  trigger.setAttribute('aria-expanded','true');
  previousOverflow = document.body.style.overflow;
  document.body.style.overflow = 'hidden';
  document.body.classList.add('aisa-menu-open');
  let branch = panel;
  while (branch.parentElement && branch !== document.body) {
   [...branch.parentElement.children].filter(el => el !== branch && !['SCRIPT','STYLE','LINK'].includes(el.tagName)).forEach(el => { background.push([el,el.inert]); el.inert = true; });
   branch = branch.parentElement;
  }
  closeButton.focus();
 }
 trigger.hidden = false;
 panel.dataset.enhanced = 'true';
 trigger.addEventListener('click', () => trigger.getAttribute('aria-expanded') === 'true' ? closeMenu() : openMenu());
 closeButton.addEventListener('click', () => closeMenu());
 nav.addEventListener('click', event => {
  const button = event.target.closest('button.wp-block-navigation-item__content');
  if (!button || !toggles.includes(button)) return;
  event.preventDefault(); event.stopImmediatePropagation();
  if (!mobile.matches && button.parentElement.classList.contains('aisa-nav-group')) return;
  const expanded = button.getAttribute('aria-expanded') !== 'true';
  const siblings = [...button.parentElement.parentElement.children];
  siblings.forEach(item => { const sibling = item.querySelector(':scope > button.wp-block-navigation-item__content'); if (sibling && sibling !== button) setExpanded(sibling,false); });
  setExpanded(button,expanded);
 }, true);
 document.addEventListener('keydown', event => {
  if (event.key === 'Escape') {
   if (panel.classList.contains('is-open')) { event.preventDefault(); closeMenu(); return; }
   const active = toggles.filter(button => button.getAttribute('aria-expanded') === 'true' && !button.parentElement.classList.contains('aisa-nav-group')).reverse().find(button => button.parentElement.contains(document.activeElement));
   if (active) { event.preventDefault(); setExpanded(active,false); active.focus(); }
  }
  if (event.key === 'Tab' && panel.classList.contains('is-open')) {
   const controls = visibleControls(); const first = controls[0]; const last = controls[controls.length-1];
   if (event.shiftKey && (document.activeElement === first || !panel.contains(document.activeElement))) { event.preventDefault(); last.focus(); }
   else if (!event.shiftKey && (document.activeElement === last || !panel.contains(document.activeElement))) { event.preventDefault(); first.focus(); }
  }
 }, true);
 document.addEventListener('focusin', event => {
  if (panel.classList.contains('is-open') && !panel.contains(event.target)) closeButton.focus();
 });
 document.addEventListener('click', event => {
  if (!panel.contains(event.target) && !trigger.contains(event.target) && !mobile.matches) toggles.forEach(button => { if (!button.parentElement.classList.contains('aisa-nav-group')) setExpanded(button,false); });
 });
 mobile.addEventListener('change', () => {
  const wasOpen = panel.classList.contains('is-open'); closeMenu(false);
  toggles.forEach(button => setExpanded(button,!mobile.matches && button.parentElement.classList.contains('aisa-nav-group')));
  if (wasOpen) (mobile.matches ? trigger : toggles[0]).focus();
 });
})();
