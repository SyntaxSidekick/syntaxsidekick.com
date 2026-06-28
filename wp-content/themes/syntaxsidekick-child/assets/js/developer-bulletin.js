(function () {
  "use strict";

  var storageKey = "syntaxsidekick-dismissed-bulletin-id";
  var bulletins = document.querySelectorAll("[data-dev-bulletin]");

  if (!bulletins.length) {
    return;
  }

  function safeGetStorage(key) {
    try {
      return localStorage.getItem(key);
    } catch (error) {
      return null;
    }
  }

  function safeSetStorage(key, value) {
    try {
      localStorage.setItem(key, value);
    } catch (error) {
      // Ignore when storage is unavailable.
    }
  }

  function prefersReducedMotion() {
    return window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  }

  function revealBulletin(bulletin) {
    bulletin.hidden = false;

    if (prefersReducedMotion()) {
      bulletin.classList.add("is-visible");
      return;
    }

    window.requestAnimationFrame(function () {
      bulletin.classList.add("is-visible");
    });
  }

  function dismissBulletin(bulletin) {
    bulletin.classList.add("is-dismissed");

    var finishDismiss = function () {
      bulletin.hidden = true;
    };

    if (prefersReducedMotion()) {
      finishDismiss();
      return;
    }

    window.setTimeout(finishDismiss, 240);
  }

  bulletins.forEach(function (bulletin) {
    var bulletinId = String(bulletin.getAttribute("data-bulletin-id") || "").trim();
    var dismissedId = String(safeGetStorage(storageKey) || "").trim();

    if (bulletinId && dismissedId === bulletinId) {
      bulletin.hidden = true;
      return;
    }

    revealBulletin(bulletin);

    var dismissButton = bulletin.querySelector("[data-dev-bulletin-dismiss]");
    if (!dismissButton || !bulletinId) {
      return;
    }

    dismissButton.addEventListener("click", function () {
      safeSetStorage(storageKey, bulletinId);
      dismissBulletin(bulletin);
    });
  });
})();
