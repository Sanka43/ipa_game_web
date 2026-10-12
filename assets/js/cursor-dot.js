(function () {
  if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  var dot = document.createElement('div');
  dot.className = 'cursor-dot';
  dot.setAttribute('aria-hidden', 'true');
  document.body.appendChild(dot);

  var tx = -100, ty = -100, x = -100, y = -100, shown = false, raf = 0;

  function tick() {
    x += (tx - x) * 0.14;
    y += (ty - y) * 0.14;
    dot.style.transform = 'translate3d(' + x + 'px,' + y + 'px,0) translate(-50%,-50%)';
    raf = (Math.abs(tx - x) > 0.1 || Math.abs(ty - y) > 0.1) ? requestAnimationFrame(tick) : 0;
  }

  document.addEventListener('mousemove', function (e) {
    tx = e.clientX; ty = e.clientY;
    if (!shown) { x = tx; y = ty; shown = true; dot.classList.add('is-on'); }
    if (!raf) raf = requestAnimationFrame(tick);
  }, { passive: true });

  document.documentElement.addEventListener('mouseleave', function () { dot.classList.remove('is-on'); shown = false; });
})();
