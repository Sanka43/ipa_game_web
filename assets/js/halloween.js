/**
 * ═══════════════════════════════════════════════════════════════
 * IPA STORE — Halloween Atmospheric Ambient FX
 * Lightweight rising embers & occasional silhouette bats.
 * High-performance, pointer-events none, zero layout thrashing.
 * ═══════════════════════════════════════════════════════════════
 */
(function () {
  'use strict';

  if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    return;
  }

  var canvas = document.createElement('canvas');
  canvas.id = 'halloween-canvas';
  canvas.setAttribute('aria-hidden', 'true');
  document.body.prepend(canvas);

  var ctx = canvas.getContext('2d');
  if (!ctx) return;

  var width = 0;
  var height = 0;
  var dpr = Math.min(window.devicePixelRatio || 1, 2);
  var rafId = 0;
  var isVisible = true;

  function resize() {
    width = window.innerWidth;
    height = window.innerHeight;
    canvas.width = width * dpr;
    canvas.height = height * dpr;
    canvas.style.width = width + 'px';
    canvas.style.height = height + 'px';
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }

  resize();
  window.addEventListener('resize', resize, { passive: true });

  // ── 1. Rising Embers ──────────────────────────────────────────
  var EMBER_COUNT = Math.min(24, Math.floor(window.innerWidth / 50));
  var embers = [];
  var colors = [
    'rgba(255, 117, 24, ',  // pumpkin orange
    'rgba(255, 159, 28, ',  // warm amber
    'rgba(157, 78, 221, ',  // spooky violet
    'rgba(255, 61, 170, '   // neon magenta accent
  ];

  function createEmber(initial) {
    return {
      x: Math.random() * width,
      y: initial ? Math.random() * height : height + Math.random() * 20,
      radius: 1 + Math.random() * 2.2,
      baseAlpha: 0.25 + Math.random() * 0.45,
      alpha: 0,
      vy: 0.4 + Math.random() * 0.8,
      vxAmp: 0.4 + Math.random() * 0.7,
      swaySpeed: 0.015 + Math.random() * 0.02,
      swayOffset: Math.random() * Math.PI * 2,
      color: colors[Math.floor(Math.random() * colors.length)]
    };
  }

  for (var i = 0; i < EMBER_COUNT; i++) {
    embers.push(createEmber(true));
  }

  // ── 2. Silhouette Bats ────────────────────────────────────────
  var bats = [];
  var nextBatSpawn = Date.now() + 2000;

  function spawnBat() {
    var goRight = Math.random() > 0.5;
    var startY = 60 + Math.random() * (height * 0.45);
    bats.push({
      x: goRight ? -60 : width + 60,
      y: startY,
      targetY: startY + (Math.random() * 80 - 40),
      speedX: (goRight ? 1 : -1) * (1.8 + Math.random() * 1.5),
      speedY: 0,
      size: 14 + Math.random() * 10,
      wingCycle: Math.random() * Math.PI * 2,
      wingSpeed: 0.22 + Math.random() * 0.08,
      direction: goRight ? 1 : -1
    });
  }

  function drawBat(b) {
    ctx.save();
    ctx.translate(b.x, b.y);
    ctx.scale(b.direction, 1);

    var wingAngle = Math.sin(b.wingCycle); // -1 (up) to 1 (down)
    var s = b.size;

    ctx.fillStyle = 'rgba(15, 12, 24, 0.75)';
    ctx.shadowColor = 'rgba(157, 78, 221, 0.4)';
    ctx.shadowBlur = 6;

    // Body
    ctx.beginPath();
    ctx.ellipse(0, 0, s * 0.18, s * 0.35, 0, 0, Math.PI * 2);
    ctx.fill();

    // Wings
    ctx.beginPath();
    // Left / trailing wing
    ctx.moveTo(-s * 0.1, -s * 0.1);
    ctx.quadraticCurveTo(-s * 0.6, -s * 0.7 * wingAngle, -s * 1.1, -s * 0.2 * wingAngle);
    ctx.quadraticCurveTo(-s * 0.6, s * 0.1, -s * 0.1, s * 0.1);
    // Right / leading wing
    ctx.moveTo(s * 0.1, -s * 0.1);
    ctx.quadraticCurveTo(s * 0.6, -s * 0.7 * wingAngle, s * 1.1, -s * 0.2 * wingAngle);
    ctx.quadraticCurveTo(s * 0.6, s * 0.1, s * 0.1, s * 0.1);
    ctx.fill();

    ctx.restore();
  }

  // ── 3. Animation Loop ─────────────────────────────────────────
  var lastTime = performance.now();

  function loop(now) {
    if (!isVisible) {
      rafId = requestAnimationFrame(loop);
      return;
    }

    var dt = Math.min((now - lastTime) / 1000, 0.1);
    lastTime = now;

    ctx.clearRect(0, 0, width, height);

    // Update and draw embers
    for (var j = 0; j < embers.length; j++) {
      var e = embers[j];
      e.y -= e.vy;
      e.x += Math.sin(now * e.swaySpeed * 0.1 + e.swayOffset) * e.vxAmp;

      // Smooth fade-in near bottom, fade-out near top
      var progress = e.y / height; // 1 at bottom, 0 at top
      if (progress > 0.85) {
        e.alpha = (1 - progress) / 0.15 * e.baseAlpha;
      } else if (progress < 0.2) {
        e.alpha = (progress / 0.2) * e.baseAlpha;
      } else {
        e.alpha = e.baseAlpha;
      }

      if (e.y < -10 || e.x < -20 || e.x > width + 20) {
        embers[j] = createEmber(false);
        continue;
      }

      ctx.beginPath();
      ctx.arc(e.x, e.y, e.radius, 0, Math.PI * 2);
      ctx.fillStyle = e.color + Math.max(0, Math.min(1, e.alpha)) + ')';
      ctx.shadowColor = e.color + '0.8)';
      ctx.shadowBlur = e.radius * 3;
      ctx.fill();
    }

    // Spawn bats periodically
    if (now >= nextBatSpawn) {
      if (bats.length < 3) spawnBat();
      nextBatSpawn = now + 6000 + Math.random() * 8000;
    }

    // Update and draw bats
    for (var k = bats.length - 1; k >= 0; k--) {
      var b = bats[k];
      b.x += b.speedX;
      b.y += (b.targetY - b.y) * 0.02 + Math.sin(b.wingCycle) * 0.8;
      b.wingCycle += b.wingSpeed;

      drawBat(b);

      if ((b.direction > 0 && b.x > width + 100) || (b.direction < 0 && b.x < -100)) {
        bats.splice(k, 1);
      }
    }

    rafId = requestAnimationFrame(loop);
  }

  rafId = requestAnimationFrame(loop);

  // Pause when tab not visible to conserve battery & CPU
  document.addEventListener('visibilitychange', function () {
    isVisible = !document.hidden;
    if (isVisible) lastTime = performance.now();
  });
})();
