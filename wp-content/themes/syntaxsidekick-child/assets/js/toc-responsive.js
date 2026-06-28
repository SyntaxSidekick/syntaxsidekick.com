document.addEventListener('DOMContentLoaded', () => {
  const toc = document.querySelector('.ss-toc, .ez-toc-container, #ez-toc-container');
  const mobileMount = document.querySelector('.ss-mobile-toc-mount');
  const desktopMount = document.querySelector('.ss-sidebar-toc-mount');

  if (!toc || !mobileMount || !desktopMount) {
    return;
  }

  const media = window.matchMedia('(max-width: 900px)');

  function placeToc() {
    const target = media.matches ? mobileMount : desktopMount;

    if (toc.parentElement !== target) {
      target.appendChild(toc);
    }
  }

  placeToc();

  if (typeof media.addEventListener === 'function') {
    media.addEventListener('change', placeToc);
  } else {
    media.addListener(placeToc);
  }
});
