(function () {
  "use strict";

  var root = document.querySelector("[data-ss-mega-nav]");
  if (!root) {
    return;
  }

  var desktopMq = window.matchMedia("(min-width: 981px)");
  var focusSelector = 'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"]), input:not([disabled]), select:not([disabled]), textarea:not([disabled])';

  var nav = root.querySelector(".ss-primary-nav");
  var menuToggle = root.querySelector(".ss-menu-toggle");
  var searchButton = root.querySelector(".ss-search-link");
  var megaMenuData = Array.isArray(window.syntaxsidekickMegaMenuData)
    ? window.syntaxsidekickMegaMenuData
    : [];

  var activeItem = null;
  var closeTimer = null;
  var overlay = null;
  var mobileClose = null;
  var megaItems = [];

  function toAbsoluteUrl(path) {
    try {
      return new URL(path, window.location.origin).toString();
    } catch (error) {
      return path;
    }
  }

  function normalizePath(path) {
    if (!path) {
      return "/";
    }

    var output = path;
    if (output.charAt(0) !== "/") {
      output = "/" + output;
    }

    if (output.length > 1 && output.charAt(output.length - 1) !== "/") {
      output += "/";
    }

    return output;
  }

  function slugify(value) {
    return value.toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/(^-|-$)/g, "");
  }

  function createElement(tagName, className, text) {
    var element = document.createElement(tagName);
    if (className) {
      element.className = className;
    }
    if (typeof text === "string") {
      element.textContent = text;
    }
    return element;
  }

  function renderMegaMenu() {
    if (!nav) {
      return;
    }

    if (!Array.isArray(megaMenuData) || !megaMenuData.length) {
      return;
    }

    var currentPath = normalizePath(window.location.pathname);

    megaMenuData.forEach(function (item) {
      if (item.forceActive) {
        item.active = true;
        return;
      }

      var paths = item.activePaths || [item.url];
      item.active = paths.some(function (path) {
        var matchPath = normalizePath(path);
        if (matchPath === "/") {
          return currentPath === "/";
        }
        return currentPath.indexOf(matchPath) === 0;
      });
    });

    nav.innerHTML = "";

    var mobileHeader = createElement("div", "ss-mobile-nav-header");
    mobileHeader.appendChild(createElement("p", null, "Browse SyntaxSidekick"));
    mobileClose = createElement("button", "ss-mobile-close", "Close");
    mobileClose.type = "button";
    mobileClose.setAttribute("aria-label", "Close navigation");
    mobileHeader.appendChild(mobileClose);

    var list = createElement("ul", "ss-nav-list");
    list.setAttribute("role", "list");

    megaMenuData.forEach(function (item, index) {
      var liClass = "ss-nav-item";
      var templateId = typeof item.panelTemplateId === "string" ? item.panelTemplateId : "";
      var panelTemplate = templateId ? document.getElementById(templateId) : null;
      var hasPanelTemplate = Boolean(panelTemplate && panelTemplate.innerHTML.trim());
      var hasMegaBehavior = Boolean(item.hasMegaMenu && hasPanelTemplate);
      var useSplitLink = hasMegaBehavior && (item.key === "tutorials" || item.key === "articles");

      if (hasMegaBehavior) {
        liClass += " ss-nav-item-has-mega";
      }
      if (item.active) {
        liClass += " is-active";
      }

      var li = createElement("li", liClass);

      if (!hasMegaBehavior) {
        var linkClass = "ss-nav-link";
        if (item.active) {
          linkClass += " is-active";
        }

        var link = createElement("a", linkClass, item.label);
        link.href = toAbsoluteUrl(item.url);
        li.appendChild(link);
        list.appendChild(li);
        return;
      }

      var slug = slugify(item.label);
      var panelId = "ss-mega-" + slug + "-" + index;

      li.setAttribute("data-ss-menu", slug);

      var trigger = createElement("button", "ss-nav-trigger", item.label);
      trigger.type = "button";
      trigger.setAttribute("aria-expanded", "false");
      trigger.setAttribute("aria-controls", panelId);
      trigger.setAttribute("aria-haspopup", "true");
      if (useSplitLink) {
        trigger.textContent = "";
        trigger.classList.add("ss-nav-trigger--icon");
        trigger.setAttribute("aria-label", "Toggle " + item.label + " menu");
      }
      trigger.appendChild(createElement("span", "ss-trigger-caret"));

      var panel = createElement("section", "ss-mega-panel");
      panel.id = panelId;
      panel.setAttribute("role", "region");
      panel.setAttribute("aria-label", item.label + " menu");
      panel.setAttribute("aria-hidden", "true");
      panel.innerHTML = panelTemplate.innerHTML;

      if (useSplitLink) {
        var splitWrap = createElement("div", "ss-nav-split");
        var splitLinkClass = "ss-nav-link";
        if (item.active) {
          splitLinkClass += " is-active";
        }

        var splitLink = createElement("a", splitLinkClass, item.label);
        splitLink.href = toAbsoluteUrl(item.url);

        splitWrap.appendChild(splitLink);
        splitWrap.appendChild(trigger);
        li.appendChild(splitWrap);
      } else {
        li.appendChild(trigger);
      }
      li.appendChild(panel);
      list.appendChild(li);
    });

    var mobileSearchItem = createElement("li", "ss-nav-item ss-nav-search-mobile");
    var mobileSearchLink = createElement("a", "ss-nav-link", "Search");
    mobileSearchLink.href = searchButton ? searchButton.href : toAbsoluteUrl("/?s=");
    mobileSearchItem.appendChild(mobileSearchLink);
    list.appendChild(mobileSearchItem);

    nav.appendChild(mobileHeader);
    nav.appendChild(list);

    overlay = root.querySelector(".ss-nav-overlay");
    if (!overlay) {
      overlay = createElement("button", "ss-nav-overlay");
      overlay.type = "button";
      overlay.setAttribute("aria-hidden", "true");
      overlay.setAttribute("tabindex", "-1");
      root.appendChild(overlay);
    }

    megaItems = Array.prototype.slice.call(root.querySelectorAll(".ss-nav-item-has-mega"));
  }

  renderMegaMenu();

  function isDesktop() {
    return desktopMq.matches;
  }

  function getPanel(item) {
    return item ? item.querySelector(".ss-mega-panel") : null;
  }

  function getFocusables(container) {
    if (!container) {
      return [];
    }

    return Array.prototype.slice.call(container.querySelectorAll(focusSelector)).filter(function (el) {
      return !el.hasAttribute("disabled") && !el.getAttribute("aria-hidden");
    });
  }

  function setPanelInteractive(panel, expanded) {
    if (!panel) {
      return;
    }

    panel.setAttribute("aria-hidden", expanded ? "false" : "true");
    panel.classList.toggle("is-open", expanded);

    var panelFocusables = panel.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]');
    panelFocusables.forEach(function (el) {
      if (expanded) {
        if (el.hasAttribute("data-ss-prev-tabindex")) {
          var previous = el.getAttribute("data-ss-prev-tabindex");
          if (previous === "") {
            el.removeAttribute("tabindex");
          } else {
            el.setAttribute("tabindex", previous);
          }
          el.removeAttribute("data-ss-prev-tabindex");
        } else if (el.getAttribute("tabindex") === "-1") {
          el.removeAttribute("tabindex");
        }
      } else {
        if (!el.hasAttribute("data-ss-prev-tabindex")) {
          el.setAttribute("data-ss-prev-tabindex", el.getAttribute("tabindex") || "");
        }
        el.setAttribute("tabindex", "-1");
      }
    });
  }

  function updatePanelPosition(panel) {
    if (!panel || !isDesktop()) {
      return;
    }

    var headerBottom = root.getBoundingClientRect().bottom;
    panel.style.top = Math.round(headerBottom + 8) + "px";
  }

  function closeAllMenus(restoreFocus) {
    var triggerToFocus = null;
    if (restoreFocus && activeItem) {
      triggerToFocus = activeItem.querySelector(".ss-nav-trigger");
    }

    megaItems.forEach(function (item) {
      var trigger = item.querySelector(".ss-nav-trigger");
      var panel = getPanel(item);

      item.classList.remove("is-open");
      trigger.setAttribute("aria-expanded", "false");
      setPanelInteractive(panel, false);
    });

    activeItem = null;
    root.classList.remove("has-open-mega");

    if (restoreFocus && triggerToFocus) {
      triggerToFocus.focus();
    }
  }

  function openMenu(item, focusFirst) {
    if (!item) {
      return;
    }

    if (activeItem && activeItem !== item) {
      closeAllMenus(false);
    }

    var trigger = item.querySelector(".ss-nav-trigger");
    var panel = getPanel(item);

    if (!panel) {
      return;
    }

    updatePanelPosition(panel);
    item.classList.add("is-open");
    trigger.setAttribute("aria-expanded", "true");
    setPanelInteractive(panel, true);

    activeItem = item;
    root.classList.add("has-open-mega");

    if (focusFirst) {
      var firstFocusable = getFocusables(panel)[0];
      if (firstFocusable) {
        firstFocusable.focus();
      }
    }
  }

  function toggleMobileNav(forceOpen, restoreFocus) {
    var isOpen = root.classList.contains("is-mobile-open");
    var shouldOpen = typeof forceOpen === "boolean" ? forceOpen : !isOpen;

    root.classList.toggle("is-mobile-open", shouldOpen);
    if (menuToggle) {
      menuToggle.setAttribute("aria-expanded", shouldOpen ? "true" : "false");
    }
    document.body.classList.toggle("ss-nav-lock", shouldOpen);

    if (overlay) {
      overlay.setAttribute("aria-hidden", shouldOpen ? "false" : "true");
    }

    if (shouldOpen && mobileClose) {
      mobileClose.focus();
    }

    if (!shouldOpen) {
      closeAllMenus(false);
      if (restoreFocus !== false && menuToggle) {
        menuToggle.focus();
      }
    }
  }

  function scheduleClose(item) {
    clearTimeout(closeTimer);
    closeTimer = window.setTimeout(function () {
      if (activeItem === item) {
        closeAllMenus(false);
      }
    }, 120);
  }

  function cancelScheduledClose() {
    clearTimeout(closeTimer);
  }

  function trapFocus(event) {
    if (event.key !== "Tab") {
      return;
    }

    if (root.classList.contains("is-mobile-open")) {
      var mobileFocusables = getFocusables(nav);
      if (!mobileFocusables.length) {
        return;
      }

      var firstMobile = mobileFocusables[0];
      var lastMobile = mobileFocusables[mobileFocusables.length - 1];

      if (event.shiftKey && document.activeElement === firstMobile) {
        event.preventDefault();
        lastMobile.focus();
      } else if (!event.shiftKey && document.activeElement === lastMobile) {
        event.preventDefault();
        firstMobile.focus();
      }
      return;
    }

    if (!isDesktop() || !activeItem) {
      return;
    }

    var trigger = activeItem.querySelector(".ss-nav-trigger");
    var panel = getPanel(activeItem);
    var panelFocusables = getFocusables(panel);
    var activeLink = activeItem.querySelector(".ss-nav-link");
    var focusables = [];

    if (activeLink) {
      focusables.push(activeLink);
    }
    if (trigger) {
      focusables.push(trigger);
    }

    focusables = focusables.concat(panelFocusables);

    if (!focusables.length) {
      return;
    }

    var first = focusables[0];
    var last = focusables[focusables.length - 1];

    if (event.shiftKey && document.activeElement === first) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
      event.preventDefault();
      first.focus();
    }
  }

  function onTriggerActivate(event, item) {
    var trigger = item.querySelector(".ss-nav-trigger");
    var expanded = trigger.getAttribute("aria-expanded") === "true";

    if (isDesktop()) {
      if (expanded) {
        closeAllMenus(true);
      } else {
        openMenu(item, true);
      }
      return;
    }

    // Mobile accordion behavior.
    if (expanded) {
      closeAllMenus(false);
    } else {
      openMenu(item, false);
    }

    event.stopPropagation();
  }

  function closeForDesktopChange() {
    closeAllMenus(false);
    if (!isDesktop()) {
      return;
    }

    root.classList.remove("is-mobile-open");
    if (menuToggle) {
      menuToggle.setAttribute("aria-expanded", "false");
    }
    document.body.classList.remove("ss-nav-lock");
  }

  megaItems.forEach(function (item) {
    var trigger = item.querySelector(".ss-nav-trigger");
    var panel = getPanel(item);

    setPanelInteractive(panel, false);

    trigger.addEventListener("click", function (event) {
      onTriggerActivate(event, item);
    });

    trigger.addEventListener("keydown", function (event) {
      if (event.key === "Enter" || event.key === " ") {
        event.preventDefault();
        onTriggerActivate(event, item);
      }
    });

    item.addEventListener("mouseenter", function () {
      if (!isDesktop()) {
        return;
      }
      cancelScheduledClose();
      openMenu(item, false);
    });

    item.addEventListener("mouseleave", function () {
      if (!isDesktop()) {
        return;
      }
      scheduleClose(item);
    });

    item.addEventListener("focusin", function () {
      if (!isDesktop()) {
        return;
      }
      cancelScheduledClose();
      openMenu(item, false);
    });

    item.addEventListener("focusout", function (event) {
      if (!isDesktop()) {
        return;
      }

      var nextTarget = event.relatedTarget;
      if (nextTarget && item.contains(nextTarget)) {
        return;
      }

      scheduleClose(item);
    });

    if (panel) {
      panel.addEventListener("mouseenter", function () {
        if (!isDesktop()) {
          return;
        }
        cancelScheduledClose();
      });

      panel.addEventListener("mouseleave", function () {
        if (!isDesktop()) {
          return;
        }
        scheduleClose(item);
      });
    }
  });

  if (menuToggle) {
    menuToggle.addEventListener("click", function () {
      toggleMobileNav();
    });
  }

  if (mobileClose) {
    mobileClose.addEventListener("click", function () {
      toggleMobileNav(false);
    });
  }

  if (overlay) {
    overlay.addEventListener("click", function () {
      toggleMobileNav(false);
    });
  }

  document.addEventListener("pointerdown", function (event) {
    if (!root.contains(event.target)) {
      closeAllMenus(false);
      if (root.classList.contains("is-mobile-open")) {
        toggleMobileNav(false, false);
      }
    }
  });

  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
      if (root.classList.contains("is-mobile-open")) {
        toggleMobileNav(false, true);
        return;
      }

      if (activeItem) {
        closeAllMenus(true);
      }
      return;
    }

    trapFocus(event);
  });

  window.addEventListener("resize", function () {
    closeForDesktopChange();
    if (activeItem) {
      updatePanelPosition(getPanel(activeItem));
    }
  });

  if (typeof desktopMq.addEventListener === "function") {
    desktopMq.addEventListener("change", closeForDesktopChange);
  } else if (typeof desktopMq.addListener === "function") {
    desktopMq.addListener(closeForDesktopChange);
  }
})();
