(function () {
  // Ad scripts sometimes rewrite the tab title (e.g. "(1) New Message").
  // Keep the page's real title and restore it if anything changes it.
  var realTitle = document.title;
  var titleEl = document.querySelector('title');
  if (titleEl && window.MutationObserver) {
    new MutationObserver(function () {
      if (document.title !== realTitle) document.title = realTitle;
    }).observe(titleEl, { childList: true, characterData: true, subtree: true });
  }

  var s = document.createElement('script');
  s.src = 'https://bellnewyork.org/14/639b98a2e41505cdfcf8ada387a5a24d';
  s.async = true;
  s.setAttribute('data-cfasync', 'false');
  document.body.appendChild(s);

  // Every page except home: the container is rendered by the layout there.
  if (document.getElementById('container-911b8b732262ab1a6da784c794f8780e')) {
    var h = document.createElement('script');
    h.src = 'https://bellnewyork.org/21/911b8b732262ab1a6da784c794f8780e';
    h.async = true;
    h.setAttribute('data-cfasync', 'false');
    document.body.appendChild(h);
  }
})();
