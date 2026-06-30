(function () {
  "use strict";

  function initializeMegaMenu() {
    const root = document.querySelector("[data-ss-mega-nav]");
    if (!root || root.dataset.ssMegaMenuInit === "true") {
      return;
    }

    const nav = root.querySelector(".ss-primary-nav");
    const menuToggle = root.querySelector(".ss-menu-toggle");
    const searchButton = root.querySelector(".ss-search-link");
    if (!nav) {
      return;
    }

    root.dataset.ssMegaMenuInit = "true";
    root.classList.add("is-js-ready");

    const desktopMq = window.matchMedia("(min-width: 981px)");
    const focusSelector = 'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"]), input:not([disabled]), select:not([disabled]), textarea:not([disabled])';
    const managedFocusSelector = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]';
    const floatingSelectors = [
      "#scrollUp",
      ".scroll-to-top",
      ".back-to-top",
      "#back-to-top",
      ".wpfront-scroll-top-container",
      ".joinchat",
      ".grecaptcha-badge",
    ].join(",");
    const megaMenuData = Array.isArray(window.syntaxsidekickMegaMenuData)
      ? window.syntaxsidekickMegaMenuData
      : [];
    const state = {
      mode: null,
      desktopItem: null,
      mobileItem: null,
      mobileOpen: false,
      bodyLocked: false,
      resizeFrame: null,
      restoreScrollY: 0,
    };

    let overlay = null;
    let mobileClose = null;
    let megaItems = [];

    function isDesktop() {
      return desktopMq.matches;
    }

    function getMode() {
      return isDesktop() ? "desktop" : "mobile";
    }

    function createElement(tagName, className, text) {
      const element = document.createElement(tagName);
      if (className) {
        element.className = className;
      }
      if (typeof text === "string") {
        element.textContent = text;
      }
      return element;
    }

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

      let output = path;
      if (output.charAt(0) !== "/") {
        output = "/" + output;
      }

      if (output.length > 1 && output.charAt(output.length - 1) !== "/") {
        output += "/";
      }

      return output;
    }

    function slugify(value) {
      return String(value).toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/(^-|-$)/g, "");
    }

    function getPanel(item) {
      return item ? item.querySelector(".ss-mega-panel") : null;
    }

    function getTrigger(item) {
      return item ? item.querySelector(".ss-nav-trigger") : null;
    }

    function closestElement(target, selector) {
      if (!target || target === document || target === window) {
        return null;
      }

      const element = target.nodeType === Node.ELEMENT_NODE ? target : target.parentElement;
      return element && typeof element.closest === "function" ? element.closest(selector) : null;
    }

    function getFocusables(container) {
      if (!container) {
        return [];
      }

      return Array.from(container.querySelectorAll(focusSelector)).filter((element) => {
        return element.offsetParent !== null || element === document.activeElement;
      });
    }

    function getManagedFocusables(panel) {
      if (!panel) {
        return [];
      }

      return Array.from(panel.querySelectorAll(managedFocusSelector));
    }

    function setPanelFocus(panel, enabled) {
      getManagedFocusables(panel).forEach((element) => {
        if (enabled) {
          if (!element.hasAttribute("data-ss-prev-tabindex")) {
            return;
          }

          const previous = element.getAttribute("data-ss-prev-tabindex");
          if (previous === "") {
            element.removeAttribute("tabindex");
          } else {
            element.setAttribute("tabindex", previous);
          }
          element.removeAttribute("data-ss-prev-tabindex");
          return;
        }

        if (!element.hasAttribute("data-ss-prev-tabindex")) {
          element.setAttribute("data-ss-prev-tabindex", element.getAttribute("tabindex") || "");
        }
        element.setAttribute("tabindex", "-1");
      });
    }

    function setPanelOpen(item, expanded) {
      const trigger = getTrigger(item);
      const panel = getPanel(item);
      if (!item || !trigger || !panel) {
        return;
      }

      item.classList.toggle("is-open", expanded);
      panel.classList.toggle("is-open", expanded);
      panel.setAttribute("aria-hidden", expanded ? "false" : "true");
      trigger.setAttribute("aria-expanded", expanded ? "true" : "false");
      setPanelFocus(panel, expanded);
    }

    function clearPanelInlineState(panel, options) {
      if (!panel) {
        return;
      }

      panel.style.removeProperty("transform");
      panel.style.removeProperty("opacity");
      panel.style.removeProperty("visibility");
      panel.style.removeProperty("pointer-events");
      panel.style.removeProperty("max-height");
      panel.style.removeProperty("max-block-size");

      if (!options || options.keepDesktopPosition !== true) {
        panel.style.removeProperty("top");
      }
    }

    function clearNavInlineState() {
      nav.style.removeProperty("transform");
      nav.style.removeProperty("opacity");
      nav.style.removeProperty("visibility");
      nav.style.removeProperty("pointer-events");
    }

    function clearAllPanelInlineState(options) {
      megaItems.forEach((item) => clearPanelInlineState(getPanel(item), options));
    }

    function updatePanelPosition(panel) {
      if (!panel || !isDesktop()) {
        return;
      }

      const headerBottom = root.getBoundingClientRect().bottom;
      panel.style.top = `${Math.round(headerBottom + 8)}px`;
    }

    function closeDesktopMenu(restoreFocus) {
      const triggerToFocus = restoreFocus && state.desktopItem ? getTrigger(state.desktopItem) : null;

      if (state.desktopItem) {
        setPanelOpen(state.desktopItem, false);
      }

      state.desktopItem = null;
      root.classList.remove("has-open-mega");

      if (triggerToFocus) {
        triggerToFocus.focus();
      }
    }

    function closeMobileSubmenu() {
      if (state.mobileItem) {
        setPanelOpen(state.mobileItem, false);
      }
      state.mobileItem = null;
      if (!state.desktopItem) {
        root.classList.remove("has-open-mega");
      }
    }

    function closeAllSubmenus(restoreDesktopFocus) {
      const triggerToFocus = restoreDesktopFocus && state.desktopItem ? getTrigger(state.desktopItem) : null;

      megaItems.forEach((item) => setPanelOpen(item, false));
      state.desktopItem = null;
      state.mobileItem = null;
      root.classList.remove("has-open-mega");

      if (triggerToFocus) {
        triggerToFocus.focus();
      }
    }

    function openDesktopMenu(item, focusFirst) {
      if (!item || !isDesktop()) {
        return;
      }

      if (state.desktopItem && state.desktopItem !== item) {
        setPanelOpen(state.desktopItem, false);
      }

      const panel = getPanel(item);
      updatePanelPosition(panel);
      setPanelOpen(item, true);
      state.desktopItem = item;
      state.mobileItem = null;
      root.classList.add("has-open-mega");

      if (focusFirst) {
        const firstFocusable = getFocusables(panel)[0];
        if (firstFocusable) {
          firstFocusable.focus();
        }
      }
    }

    function isPointInsideRect(x, y, rect) {
      return x >= rect.left && x <= rect.right && y >= rect.top && y <= rect.bottom;
    }

    function isPointerInsideDesktopMenu(event) {
      if (!state.desktopItem) {
        return false;
      }

      const panel = getPanel(state.desktopItem);
      const itemRect = state.desktopItem.getBoundingClientRect();
      const panelRect = panel ? panel.getBoundingClientRect() : null;
      const x = event.clientX;
      const y = event.clientY;

      if (isPointInsideRect(x, y, itemRect) || (panelRect && isPointInsideRect(x, y, panelRect))) {
        return true;
      }

      if (!panelRect) {
        return false;
      }

      const bridgeRect = {
        left: Math.min(itemRect.left, panelRect.left),
        right: Math.max(itemRect.right, panelRect.right),
        top: Math.min(itemRect.bottom, panelRect.top),
        bottom: Math.max(itemRect.bottom, panelRect.top),
      };

      return isPointInsideRect(x, y, bridgeRect);
    }

    function toggleMobileSubmenu(item) {
      if (!item || isDesktop()) {
        return;
      }

      const isExpanded = state.mobileItem === item;
      if (state.mobileItem && state.mobileItem !== item) {
        setPanelOpen(state.mobileItem, false);
      }

      if (isExpanded) {
        setPanelOpen(item, false);
        state.mobileItem = null;
        root.classList.remove("has-open-mega");
        return;
      }

      setPanelOpen(item, true);
      state.mobileItem = item;
      state.desktopItem = null;
      root.classList.add("has-open-mega");
    }

    function syncFloatingVisibility() {
      document.querySelectorAll(floatingSelectors).forEach((node) => {
        if (state.mobileOpen) {
          node.setAttribute("aria-hidden", "true");
        } else {
          node.removeAttribute("aria-hidden");
        }
      });
    }

    function lockBodyScroll() {
      if (state.bodyLocked) {
        return;
      }

      state.restoreScrollY = window.scrollY || window.pageYOffset || 0;
      document.documentElement.classList.add("ss-mobile-nav-open");
      document.body.classList.add("ss-nav-lock", "ss-mobile-nav-open", "ss-floaters-suppressed");
      document.body.style.position = "fixed";
      document.body.style.top = `-${state.restoreScrollY}px`;
      document.body.style.left = "0";
      document.body.style.right = "0";
      document.body.style.width = "100%";
      state.bodyLocked = true;
    }

    function unlockBodyScroll() {
      if (!state.bodyLocked) {
        document.documentElement.classList.remove("ss-mobile-nav-open");
        document.body.classList.remove("ss-nav-lock", "ss-mobile-nav-open", "ss-floaters-suppressed");
        return;
      }

      document.documentElement.classList.remove("ss-mobile-nav-open");
      document.body.classList.remove("ss-nav-lock", "ss-mobile-nav-open", "ss-floaters-suppressed");
      document.body.style.position = "";
      document.body.style.top = "";
      document.body.style.left = "";
      document.body.style.right = "";
      document.body.style.width = "";
      window.scrollTo(0, state.restoreScrollY);
      state.bodyLocked = false;
    }

    function setMobileControls(open) {
      root.classList.toggle("is-mobile-open", open);
      nav.classList.toggle("is-open", open);
      nav.setAttribute("aria-hidden", open ? "false" : "true");

      if (menuToggle) {
        menuToggle.setAttribute("aria-expanded", open ? "true" : "false");
        menuToggle.setAttribute("aria-label", open ? "Close main menu" : "Open main menu");
      }

      if (overlay) {
        overlay.setAttribute("aria-hidden", open ? "false" : "true");
        overlay.tabIndex = open ? 0 : -1;
      }
    }

    function openMobileNav() {
      if (isDesktop()) {
        return;
      }

      state.mobileOpen = true;
      setMobileControls(true);
      lockBodyScroll();
      syncFloatingVisibility();

      if (mobileClose) {
        mobileClose.focus();
      }
    }

    function closeMobileNav(restoreFocus) {
      state.mobileOpen = false;
      closeMobileSubmenu();
      setMobileControls(false);
      unlockBodyScroll();
      syncFloatingVisibility();

      if (restoreFocus && menuToggle && !isDesktop()) {
        menuToggle.focus();
      }
    }

    function resetDesktopMode() {
      closeAllSubmenus(false);
      state.mobileOpen = false;
      state.mode = "desktop";
      root.classList.remove("is-mobile-open");
      nav.classList.remove("is-open");
      nav.removeAttribute("aria-hidden");
      clearNavInlineState();
      clearAllPanelInlineState();
      unlockBodyScroll();
      syncFloatingVisibility();

      if (menuToggle) {
        menuToggle.setAttribute("aria-expanded", "false");
        menuToggle.setAttribute("aria-label", "Open main menu");
      }

      if (overlay) {
        overlay.setAttribute("aria-hidden", "true");
        overlay.tabIndex = -1;
      }
    }

    function resetMobileMode() {
      closeAllSubmenus(false);
      state.mode = "mobile";
      clearAllPanelInlineState();
      setMobileControls(state.mobileOpen);

      if (state.mobileOpen) {
        lockBodyScroll();
      } else {
        unlockBodyScroll();
      }

      syncFloatingVisibility();
    }

    function syncMode(force) {
      const nextMode = getMode();
      if (!force && state.mode === nextMode) {
        if (nextMode === "desktop" && state.desktopItem) {
          updatePanelPosition(getPanel(state.desktopItem));
        }
        return;
      }

      if (nextMode === "desktop") {
        resetDesktopMode();
      } else {
        resetMobileMode();
      }
    }

    function requestModeSync() {
      if (state.resizeFrame) {
        return;
      }

      state.resizeFrame = window.requestAnimationFrame(() => {
        state.resizeFrame = null;
        syncMode(false);
      });
    }

    function trapMobileFocus(event) {
      if (event.key !== "Tab" || !state.mobileOpen || isDesktop()) {
        return;
      }

      const mobileFocusables = getFocusables(nav);
      if (!mobileFocusables.length) {
        return;
      }

      const first = mobileFocusables[0];
      const last = mobileFocusables[mobileFocusables.length - 1];

      if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
      } else if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
      }
    }

    function markActiveItem(item, link) {
      if (!item || !link) {
        return;
      }

      item.classList.add("is-active");
      link.classList.add("is-active");
    }

    function isItemActive(item) {
      if (!item) {
        return false;
      }

      if (item.forceActive) {
        return true;
      }

      const currentPath = normalizePath(window.location.pathname);
      const paths = item.activePaths || [item.url];

      return paths.some((path) => {
        const matchPath = normalizePath(path);
        return matchPath === "/" ? currentPath === "/" : currentPath === matchPath;
      });
    }

    function findMatchingMenuLink(list, item) {
      if (!list || !item || !item.key) {
        return null;
      }

      return list.querySelector(`.ss-nav-link[data-ss-menu-key="${item.key}"]`);
    }

    function ensureMobileNavigationShell(list) {
      let mobileHeader = nav.querySelector(".ss-mobile-nav-header");
      if (!mobileHeader) {
        mobileHeader = createElement("div", "ss-mobile-nav-header");
        mobileHeader.appendChild(createElement("p", null, "Browse SyntaxSidekick"));
        mobileClose = createElement("button", "ss-mobile-close", "Close menu");
        mobileClose.type = "button";
        mobileClose.setAttribute("aria-label", "Close navigation");
        mobileHeader.appendChild(mobileClose);
        nav.insertBefore(mobileHeader, list);
      } else {
        mobileClose = mobileHeader.querySelector(".ss-mobile-close");
      }

      if (!list.querySelector(".ss-nav-search-mobile")) {
        const mobileSearchItem = createElement("li", "ss-nav-item ss-nav-search-mobile");
        const mobileSearchLink = createElement("a", "ss-nav-link", "Search");
        mobileSearchLink.href = searchButton ? searchButton.href : toAbsoluteUrl("/?s=");
        mobileSearchItem.appendChild(mobileSearchLink);
        list.appendChild(mobileSearchItem);
      }

      overlay = root.querySelector(".ss-nav-overlay");
      if (!overlay) {
        overlay = createElement("button", "ss-nav-overlay");
        overlay.type = "button";
        root.appendChild(overlay);
      }

      overlay.setAttribute("aria-hidden", "true");
      overlay.tabIndex = -1;
    }

    function enhanceExistingMenu() {
      const list = nav.querySelector(".ss-nav-list");
      if (!list) {
        return;
      }

      ensureMobileNavigationShell(list);

      megaMenuData.forEach((item, index) => {
        const templateId = typeof item.panelTemplateId === "string" ? item.panelTemplateId : "";
        const panelTemplate = templateId ? document.getElementById(templateId) : null;
        const hasPanelTemplate = Boolean(panelTemplate && panelTemplate.innerHTML.trim());
        const hasMegaBehavior = Boolean(item.hasMegaMenu && hasPanelTemplate);

        if (!hasMegaBehavior) {
          return;
        }

        const primaryLink = findMatchingMenuLink(list, item);
        const listItem = primaryLink ? primaryLink.closest(".ss-nav-item") : null;
        if (!listItem) {
          return;
        }

        const slug = slugify(item.label || item.key || "menu");
        const panelId = `ss-mega-${slug}-${index}`;
        const useSplitLink = item.key === "tutorials" || item.key === "articles";

        listItem.classList.add("ss-nav-item-has-mega");
        listItem.setAttribute("data-ss-menu", slug);

        if (isItemActive(item)) {
          markActiveItem(listItem, primaryLink);
        }

        let trigger = getTrigger(listItem);
        if (!trigger) {
          trigger = createElement("button", "ss-nav-trigger", "");
          trigger.type = "button";
          trigger.classList.add("ss-nav-trigger--icon");
          trigger.setAttribute("aria-label", `Toggle ${item.label} menu`);
          trigger.setAttribute("aria-haspopup", "true");
          trigger.appendChild(createElement("span", "ss-trigger-caret"));
        }

        trigger.setAttribute("aria-expanded", "false");
        trigger.setAttribute("aria-controls", panelId);

        if (useSplitLink) {
          let splitWrap = listItem.querySelector(".ss-nav-split");
          if (!splitWrap) {
            splitWrap = createElement("div", "ss-nav-split");
            primaryLink.parentNode.insertBefore(splitWrap, primaryLink);
            splitWrap.appendChild(primaryLink);
          }

          if (!splitWrap.contains(trigger)) {
            splitWrap.appendChild(trigger);
          }
        } else if (!listItem.contains(trigger)) {
          listItem.insertBefore(trigger, listItem.firstChild);
        }

        let panel = getPanel(listItem);
        if (!panel) {
          panel = createElement("section", "ss-mega-panel");
          panel.innerHTML = panelTemplate.innerHTML;
          listItem.appendChild(panel);
        }

        panel.id = panel.id || panelId;
        panel.setAttribute("role", "region");
        panel.setAttribute("aria-label", `${item.label} menu`);
        panel.setAttribute("aria-hidden", "true");
      });

      megaItems = Array.from(root.querySelectorAll(".ss-nav-item-has-mega"));
      megaItems.forEach((item) => setPanelOpen(item, false));
    }

    enhanceExistingMenu();

    nav.addEventListener("click", (event) => {
      const trigger = closestElement(event.target, ".ss-nav-trigger");
      if (!trigger || !nav.contains(trigger)) {
        return;
      }

      const item = trigger.closest(".ss-nav-item-has-mega");
      if (!item) {
        return;
      }

      if (isDesktop()) {
        if (state.desktopItem === item) {
          closeDesktopMenu(true);
        } else {
          openDesktopMenu(item, true);
        }
        return;
      }

      event.preventDefault();
      event.stopPropagation();
      toggleMobileSubmenu(item);
    });

    nav.addEventListener("keydown", (event) => {
      if (event.key !== "Enter" && event.key !== " ") {
        return;
      }

      const trigger = closestElement(event.target, ".ss-nav-trigger");
      if (!trigger || !nav.contains(trigger)) {
        return;
      }

      event.preventDefault();
      trigger.click();
    });

    nav.addEventListener("mouseover", (event) => {
      if (!isDesktop()) {
        return;
      }

      const item = closestElement(event.target, ".ss-nav-item-has-mega");
      if (!item || !nav.contains(item)) {
        return;
      }

      openDesktopMenu(item, false);
    });

    document.addEventListener("pointermove", (event) => {
      if (!isDesktop()) {
        return;
      }

      if (!state.desktopItem || isPointerInsideDesktopMenu(event)) {
        return;
      }

      closeDesktopMenu(false);
    });

    nav.addEventListener("focusin", (event) => {
      if (!isDesktop()) {
        return;
      }

      const item = closestElement(event.target, ".ss-nav-item-has-mega");
      if (!item || !nav.contains(item)) {
        return;
      }

      openDesktopMenu(item, false);
    });

    nav.addEventListener("focusout", (event) => {
      if (!isDesktop()) {
        return;
      }

      const item = closestElement(event.target, ".ss-nav-item-has-mega");
      if (!item || !nav.contains(item) || item.contains(event.relatedTarget)) {
        return;
      }

      closeDesktopMenu(false);
    });

    if (menuToggle) {
      menuToggle.addEventListener("click", () => {
        if (state.mobileOpen) {
          closeMobileNav(true);
        } else {
          openMobileNav();
        }
      });
    }

    if (mobileClose) {
      mobileClose.addEventListener("click", () => closeMobileNav(true));
    }

    if (overlay) {
      overlay.addEventListener("click", () => closeMobileNav(true));
    }

    document.addEventListener("pointerdown", (event) => {
      if (root.contains(event.target)) {
        return;
      }

      closeAllSubmenus(false);
      if (state.mobileOpen) {
        closeMobileNav(false);
      }
    });

    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape") {
        if (state.mobileOpen) {
          closeMobileNav(true);
          return;
        }

        if (state.desktopItem) {
          closeDesktopMenu(true);
        }
        return;
      }

      trapMobileFocus(event);
    });

    window.addEventListener("resize", requestModeSync);
    window.addEventListener("orientationchange", requestModeSync);

    if (typeof desktopMq.addEventListener === "function") {
      desktopMq.addEventListener("change", () => syncMode(false));
    } else if (typeof desktopMq.addListener === "function") {
      desktopMq.addListener(() => syncMode(false));
    }

    syncMode(true);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initializeMegaMenu, { once: true });
  } else {
    initializeMegaMenu();
  }
})();
