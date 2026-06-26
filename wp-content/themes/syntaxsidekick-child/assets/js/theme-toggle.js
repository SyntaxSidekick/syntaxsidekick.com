(function () {
  "use strict";

  var root = document.documentElement;
  var storageKey = "syntaxsidekick-theme";
  var legacyStorageKey = "ss-theme";
  var prefersDarkMq = window.matchMedia("(prefers-color-scheme: dark)");

  function normalizeTheme(value) {
    return value === "dark" || value === "light" ? value : null;
  }

  function getStoredTheme() {
    try {
      var stored = normalizeTheme(localStorage.getItem(storageKey));
      if (stored) {
        return stored;
      }

      var legacy = normalizeTheme(localStorage.getItem(legacyStorageKey));
      if (legacy) {
        localStorage.setItem(storageKey, legacy);
        return legacy;
      }
    } catch (error) {
      return null;
    }

    return null;
  }

  function setStoredTheme(theme) {
    try {
      localStorage.setItem(storageKey, theme);
      localStorage.removeItem(legacyStorageKey);
    } catch (error) {
      // Intentionally ignored when storage is not available.
    }
  }

  function getAutoTheme() {
    return prefersDarkMq.matches ? "dark" : "light";
  }

  function getCurrentTheme() {
    var attrTheme = normalizeTheme(root.getAttribute("data-theme"));
    if (attrTheme) {
      return attrTheme;
    }

    var storedTheme = getStoredTheme();
    if (storedTheme) {
      return storedTheme;
    }

    return getAutoTheme();
  }

  function getLabels(theme) {
    if (theme === "dark") {
      return {
        label: "Switch to light mode",
        pressed: "true",
      };
    }

    return {
      label: "Switch to dark mode",
      pressed: "false",
    };
  }

  function syncButtons(theme) {
    var labels = getLabels(theme);
    var buttons = document.querySelectorAll("[data-theme-toggle]");

    buttons.forEach(function (button) {
      button.setAttribute("aria-label", labels.label);
      button.setAttribute("aria-pressed", labels.pressed);
      button.setAttribute("data-theme-current", theme);
    });
  }

  function applyTheme(theme, persist) {
    root.setAttribute("data-theme", theme);

    if (persist) {
      setStoredTheme(theme);
    }

    syncButtons(theme);
  }

  var hasStoredPreference = Boolean(getStoredTheme());
  var activeTheme = getCurrentTheme();
  applyTheme(activeTheme, false);

  document.addEventListener("click", function (event) {
    var button = event.target.closest("[data-theme-toggle]");
    if (!button) {
      return;
    }

    activeTheme = activeTheme === "dark" ? "light" : "dark";
    hasStoredPreference = true;
    applyTheme(activeTheme, true);
  });

  if (typeof prefersDarkMq.addEventListener === "function") {
    prefersDarkMq.addEventListener("change", function () {
      if (hasStoredPreference) {
        return;
      }

      activeTheme = getAutoTheme();
      applyTheme(activeTheme, false);
    });
  } else if (typeof prefersDarkMq.addListener === "function") {
    prefersDarkMq.addListener(function () {
      if (hasStoredPreference) {
        return;
      }

      activeTheme = getAutoTheme();
      applyTheme(activeTheme, false);
    });
  }
})();
