(function () {
 'use strict';
 document.querySelectorAll('[data-aisa-slideshow]').forEach(function (root) {
  if (root.hasAttribute('data-enhanced')) return;
  var slides = Array.from(root.querySelectorAll('.slideshow__slide'));
  var controls = root.querySelector('.slideshow__controls');
  var play = root.querySelector('[data-play]');
  var status = root.querySelector('[data-status]');
  var current = 0, timer = null, playing = false;
  function show(index) {
   current = (index + slides.length) % slides.length;
   slides.forEach(function (slide, i) { slide.hidden = i !== current; slide.setAttribute('aria-hidden', String(i !== current)); });
   status.textContent = status.dataset.format.replace('%1$d', current + 1).replace('%2$d', slides.length);
  }
  function stop() {
   clearInterval(timer); timer = null; playing = false;
   play.textContent = play.dataset.start; play.setAttribute('aria-label', play.dataset.start);
  }
  root.querySelector('[data-prev]').addEventListener('click', function () { stop(); show(current - 1); });
  root.querySelector('[data-next]').addEventListener('click', function () { stop(); show(current + 1); });
  play.addEventListener('click', function () {
   if (playing) { stop(); return; }
   playing = true; play.textContent = play.dataset.stop; play.setAttribute('aria-label', play.dataset.stop);
   timer = setInterval(function () { show(current + 1); }, 6000);
  });
  // Focus/hover stops rotation until the visitor explicitly starts it again.
  root.addEventListener('focusin', stop);
  root.addEventListener('mouseenter', stop);
  document.addEventListener('visibilitychange', function () { if (document.hidden) stop(); });
  root.setAttribute('data-enhanced', ''); show(0);
  controls.hidden = slides.length < 2;
 });
}());
