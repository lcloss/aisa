/* Header search: without JS the button is a link to the search page; with JS it opens a panel. */
(() => {
 'use strict';
 const trigger = document.querySelector('.site-header__search');
 const panel = document.getElementById('aisa-search-panel');
 const input = panel && panel.querySelector('input[type="search"]');
 if (!trigger || !panel || !input) return;
 trigger.setAttribute('role', 'button');
 trigger.setAttribute('aria-controls', panel.id);
 trigger.setAttribute('aria-expanded', 'false');
 const setOpen = (open, restore = false) => {
  panel.hidden = !open;
  trigger.setAttribute('aria-expanded', String(open));
  if (open) input.focus(); else if (restore) trigger.focus();
 };
 trigger.addEventListener('click', event => { event.preventDefault(); setOpen(panel.hidden); });
 trigger.addEventListener('keydown', event => { if (event.key === ' ') { event.preventDefault(); setOpen(panel.hidden); } });
 document.addEventListener('keydown', event => {
  if (event.key === 'Escape' && !panel.hidden && !document.body.classList.contains('aisa-menu-open')) { event.preventDefault(); setOpen(false, true); }
 });
})();
