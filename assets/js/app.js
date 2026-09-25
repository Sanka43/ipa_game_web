/* IPA Store — cinematic interactions. No dependencies. */
(() => {
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];
  const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const root = document.documentElement;
  root.classList.add('js');

  /* ── Reveal on scroll ─────────────────────────────── */
  const io = new IntersectionObserver(entries => entries.forEach(e => {
    if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
  }), { rootMargin: '0px 0px -8% 0px', threshold: 0.05 });
  // Anything already on screen animates in right away (don't wait for the observer: better LCP).
  $$('.reveal').forEach(el => el.getBoundingClientRect().top < innerHeight ? requestAnimationFrame(() => el.classList.add('in')) : io.observe(el));

  /* ── Top bar + scroll-linked effects (one rAF loop) ─ */
  const topbar = $('#topbar'), readbar = $('#readbar i'), dlbar = $('#dlbar'), hero = $('#hero');
  let ticking = false;
  const onScroll = () => {
    const y = scrollY;
    topbar?.classList.toggle('solid', y > 40);
    if (readbar) {
      const h = document.body.scrollHeight - innerHeight;
      readbar.style.transform = `scaleX(${h > 0 ? Math.min(1, y / h) : 0})`;
    }
    if (dlbar) dlbar.classList.toggle('show', y > 520);
    if (hero && !reduced && y < innerHeight) {
      const stage = $('.hero-stage', hero);
      if (stage) stage.style.transform = `translateY(${y * 0.18}px)`;
      $('.hero-copy', hero).style.opacity = String(1 - Math.min(1, y / (innerHeight * 0.8)));
    }
    ticking = false;
  };
  addEventListener('scroll', () => { if (!ticking) { ticking = true; requestAnimationFrame(onScroll); } }, { passive: true });
  onScroll();

  /* ── Mobile menu ──────────────────────────────────── */
  const menuBtn = $('#menuBtn');
  menuBtn?.addEventListener('click', () => {
    const open = document.body.classList.toggle('menu-open');
    menuBtn.setAttribute('aria-expanded', String(open));
  });

  /* ── Hero: letterbox intro + slideshow ────────────── */
  if (hero) {
    let introSeen = false;
    try { introSeen = sessionStorage.getItem('intro') === '1'; sessionStorage.setItem('intro', '1'); } catch {}
    if (!introSeen && !reduced) {
      hero.classList.add('intro');
      requestAnimationFrame(() => setTimeout(() => hero.classList.remove('intro'), 120));
    }

    const MS = 7000;
    hero.style.setProperty('--hero-ms', MS + 'ms');
    const bgs = $$('.hero-bg-img', hero), phones = $$('.phones', hero), cards = $$('.ns-card', hero), bars = $$('.ns-progress button', hero);
    let i = 0, timer;
    const show = n => {
      i = (n + bgs.length) % bgs.length;
      [bgs, phones, cards].forEach(list => list.forEach((el, k) => el.classList.toggle('on', k === i)));
      cards.forEach((c, k) => c.tabIndex = k === i ? 0 : -1);
      bars.forEach((b, k) => { b.classList.remove('run'); b.classList.toggle('done', k < i); });
      void bars[i]?.offsetWidth;                          // restart the progress animation
      bars[i]?.classList.add('run');
      clearTimeout(timer);
      if (!reduced) timer = setTimeout(() => show(i + 1), MS);
    };
    bars.forEach((b, k) => b.addEventListener('click', () => show(k)));
    if (bgs.length) show(0);

    // Subtle 3D parallax on the phone stack
    const stage = $('.hero-stage', hero);
    if (stage && !reduced && matchMedia('(pointer: fine)').matches) {
      hero.addEventListener('pointermove', e => {
        const x = e.clientX / innerWidth - 0.5, y = e.clientY / innerHeight - 0.5;
        $$('.phones', stage).forEach(p => p.style.transform = `rotateY(${x * 10}deg) rotateX(${-y * 8}deg)`);
      });
    }
  }

  /* ── Drag-to-scroll strips ────────────────────────── */
  $$('[data-drag]').forEach(el => {
    let down = false, sx = 0, sl = 0, moved = false;
    el.addEventListener('pointerdown', e => { if (e.pointerType !== 'mouse') return; down = true; moved = false; sx = e.clientX; sl = el.scrollLeft; });
    addEventListener('pointerup', () => { down = false; el.classList.remove('dragging'); });
    el.addEventListener('pointermove', e => {
      if (!down) return;
      const dx = e.clientX - sx;
      if (Math.abs(dx) > 5) { moved = true; el.classList.add('dragging'); }
      el.scrollLeft = sl - dx;
    });
    el.addEventListener('click', e => { if (moved) { e.preventDefault(); e.stopPropagation(); } }, true);
  });

  /* ── Poster tilt ──────────────────────────────────── */
  if (!reduced && matchMedia('(pointer: fine)').matches) {
    $$('.poster').forEach(p => {
      p.addEventListener('pointermove', e => {
        const r = p.getBoundingClientRect();
        const x = (e.clientX - r.left) / r.width - 0.5, y = (e.clientY - r.top) / r.height - 0.5;
        p.style.transform = `perspective(800px) rotateY(${x * 10}deg) rotateX(${-y * 10}deg) translateY(-4px)`;
      });
      p.addEventListener('pointerleave', () => p.style.transform = '');
    });
  }

  /* ── Live search suggestions ──────────────────────── */
  $$('form[data-suggest]').forEach(form => {
    const input = $('input', form), box = $('.suggest', form);
    let t, ctrl, hl = -1;
    const esc = s => String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const close = () => { box.hidden = true; hl = -1; };
    input.addEventListener('input', () => {
      clearTimeout(t);
      const q = input.value.trim();
      if (q.length < 2) return close();
      t = setTimeout(async () => {
        ctrl?.abort(); ctrl = new AbortController();
        try {
          const res = await fetch(`${window.SITE_BASE}/api/search?q=${encodeURIComponent(q)}`, { signal: ctrl.signal });
          const items = await res.json();
          box.innerHTML = items.map(it => `<a href="${esc(it.url)}"><img src="${esc(it.icon)}" alt="" loading="lazy"><span><b>${esc(it.name)}</b><small>${esc(it.dev)} · ${esc(it.ver)}</small></span></a>`).join('')
            + `<a class="all" href="${window.SITE_BASE}/search/?q=${encodeURIComponent(q)}">See all results for “${esc(q)}” →</a>`;
          box.hidden = false; hl = -1;
        } catch {}
      }, 180);
    });
    input.addEventListener('keydown', e => {
      const links = $$('a', box);
      if (box.hidden || !links.length) return;
      if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
        e.preventDefault();
        hl = (hl + (e.key === 'ArrowDown' ? 1 : -1) + links.length) % links.length;
        links.forEach((a, k) => a.classList.toggle('hl', k === hl));
      } else if (e.key === 'Enter' && hl >= 0) { e.preventDefault(); location.href = links[hl].href; }
      else if (e.key === 'Escape') close();
    });
    document.addEventListener('click', e => { if (!form.contains(e.target)) close(); });
  });

  /* ── Screenshot tabs ──────────────────────────────── */
  $$('.tabs').forEach(tabs => tabs.addEventListener('click', e => {
    const b = e.target.closest('[data-tab]'); if (!b) return;
    $$('[data-tab]', tabs).forEach(x => x.setAttribute('aria-selected', String(x === b)));
    $$('[data-panel]', tabs.closest('section')).forEach(p => p.hidden = p.dataset.panel !== b.dataset.tab);
  }));

  /* ── Lightbox ─────────────────────────────────────── */
  document.addEventListener('click', e => {
    const a = e.target.closest('[data-lightbox]'); if (!a) return;
    e.preventDefault();
    const set = $$(`[data-lightbox="${a.dataset.lightbox}"]`);
    let k = set.indexOf(a);
    const lb = document.createElement('div');
    lb.className = 'lightbox'; lb.setAttribute('role', 'dialog'); lb.setAttribute('aria-modal', 'true');
    lb.innerHTML = '<img alt=""><button class="lb-x" aria-label="Close">×</button><button class="lb-prev" aria-label="Previous">‹</button><button class="lb-next" aria-label="Next">›</button>';
    const img = $('img', lb);
    const go = n => { k = (n + set.length) % set.length; img.src = set[k].href; img.alt = $('img', set[k]).alt; };
    const shut = () => { lb.remove(); document.removeEventListener('keydown', key); a.focus(); };
    const key = ev => { if (ev.key === 'Escape') shut(); if (ev.key === 'ArrowRight') go(k + 1); if (ev.key === 'ArrowLeft') go(k - 1); };
    lb.addEventListener('click', ev => {
      if (ev.target.closest('.lb-next')) go(k + 1);
      else if (ev.target.closest('.lb-prev')) go(k - 1);
      else if (ev.target === lb || ev.target.closest('.lb-x')) shut();
    });
    document.addEventListener('keydown', key);
    document.body.append(lb); go(k); $('.lb-x', lb).focus();
  });

  /* ── Long description clamp ───────────────────────── */
  $$('[data-clamp]').forEach(c => {
    const btn = c.parentElement.querySelector('[data-more]');
    if (c.scrollHeight > c.clientHeight + 40) {
      c.classList.add('clamped'); btn.hidden = false;
      btn.addEventListener('click', () => { c.classList.add('open'); c.classList.remove('clamped'); btn.remove(); });
    }
  });

  /* ── Download page: countdown ring, then reveal the button ─ */
  const ring = $('[data-countdown]');
  const secs = ring ? +ring.dataset.countdown : 0;
  if (ring && secs > 0) {
    const prog = $('.dl-prog', ring), count = $('[data-count]'), wait = $('[data-wait]');
    const finish = () => {
      ring.classList.add('done');
      wait?.remove();
      $$('[data-dl]').forEach(el => el.hidden = false);
      $('.dl-btn')?.focus({ preventScroll: true });
    };
    if (reduced) finish();
    else {
      prog.style.transition = `stroke-dashoffset ${secs}s linear`;
      requestAnimationFrame(() => requestAnimationFrame(() => prog.style.strokeDashoffset = '0'));
      let left = secs;
      const tick = setInterval(() => {
        left--;
        if (count) count.textContent = String(Math.max(left, 0));
        if (left <= 0) { clearInterval(tick); finish(); }
      }, 1000);
    }
  }

  /* ── IPAStore page: profiles only install on iOS ──── */
  // iPads report a Mac user agent, so a touch-capable "Mac" counts as iOS too.
  const isIOS = /iPhone|iPad|iPod/.test(navigator.userAgent) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
  const iosHint = $('[data-ios-hint]');
  if (iosHint) iosHint.hidden = isIOS;

  // On a computer or Android, the download button leads to the "open on your iPhone" page instead.
  document.addEventListener('click', e => {
    const a = e.target.closest('a[href$="/dl/ipastore/"]');
    if (!a || isIOS) return;
    e.preventDefault();
    location.href = `${window.SITE_BASE}/download-ipastore/open-on-iphone/`;
  });

  // QR code pointing back to the download page.
  const qr = $('[data-qr]');
  if (qr) {
    if (window.QRCode) new QRCode(qr, { text: qr.dataset.qr, width: 200, height: 200, colorDark: '#05060a', colorLight: '#ffffff', correctLevel: QRCode.CorrectLevel.M });
    else qr.hidden = true;
  }

  // Back button: return to the previous page on this site, or fall back to its link.
  $$('[data-back]').forEach(b => b.addEventListener('click', e => {
    let sameSite = false;
    try { sameSite = document.referrer && new URL(document.referrer).origin === location.origin; } catch {}
    if (sameSite && history.length > 1) { e.preventDefault(); history.back(); }
  }));

  /* ── Copy buttons ─────────────────────────────────── */
  document.addEventListener('click', async e => {
    const b = e.target.closest('[data-copy]'); if (!b) return;
    try { await navigator.clipboard.writeText(b.dataset.copy); b.textContent = 'Copied ✓'; b.classList.add('ok'); }
    catch { b.textContent = 'Select & copy'; }
    setTimeout(() => { b.textContent = 'Copy'; b.classList.remove('ok'); }, 2000);
  });

  /* ── Active TOC link ──────────────────────────────── */
  const tocLinks = $$('.toc a[href^="#"]');
  if (tocLinks.length) {
    const map = new Map(tocLinks.map(a => [decodeURIComponent(a.hash.slice(1)), a]));
    const tio = new IntersectionObserver(es => es.forEach(e => {
      if (e.isIntersecting) { tocLinks.forEach(a => a.classList.remove('on')); map.get(e.target.id)?.classList.add('on'); }
    }), { rootMargin: '-20% 0px -70% 0px' });
    map.forEach((_, id) => { const el = document.getElementById(id); if (el) tio.observe(el); });
  }
})();
