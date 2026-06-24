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

  var megaMenuData = [
    {
      label: "Home",
      url: "/",
      hasMegaMenu: false,
      active: false,
      activePaths: ["/"],
      columns: [],
      cta: null
    },
    {
      label: "Articles",
      url: "/articles/",
      hasMegaMenu: true,
      active: false,
      activePaths: ["/articles/", "/category/"],
      columns: [
        {
          title: "Browse Categories",
          type: "linkList",
          items: [
            { label: "Front-End", url: "/category/front-end/" },
            { label: "UX Engineering", url: "/category/ux-engineering/" },
            { label: "Accessibility", url: "/category/accessibility/" },
            { label: "Performance", url: "/category/performance/" },
            { label: "Architecture", url: "/category/architecture/" },
            { label: "AI & Development", url: "/category/ai-development/" },
            { label: "Design Systems", url: "/category/design-systems/" },
            { label: "Career Growth", url: "/category/career-growth/" }
          ]
        },
        {
          title: "Popular Articles",
          type: "storyList",
          items: [
            { label: "Is Sass Dead?", url: "/articles/is-sass-dead/" },
            { label: "AI Isn't Replacing Developers", url: "/articles/ai-isnt-replacing-developers/" },
            { label: "Design Systems vs Component Libraries", url: "/articles/design-systems-vs-component-libraries/" },
            { label: "Why Core Web Vitals Matter", url: "/articles/why-core-web-vitals-matter/" },
            { label: "Building Better Developer Experience", url: "/articles/building-better-developer-experience/" }
          ]
        }
      ],
      cta: {
        label: "View all articles",
        url: "/articles/"
      }
    },
    {
      label: "Tutorials",
      url: "/tutorials/",
      hasMegaMenu: true,
      active: false,
      activePaths: ["/tutorials/"],
      columns: [
        {
          title: "Browse Categories",
          type: "categoryList",
          items: [
            { label: "All Tutorials", url: "/tutorials/", count: 42, iconText: "AT", iconClass: "ss-topic-grid", highlight: true },
            { label: "HTML", url: "/tutorials/html/", count: 6, iconText: "H", iconClass: "ss-topic-html" },
            { label: "CSS", url: "/tutorials/css/", count: 8, iconText: "C", iconClass: "ss-topic-css" },
            { label: "JavaScript", url: "/tutorials/javascript/", count: 12, iconText: "JS", iconClass: "ss-topic-js" },
            { label: "TypeScript", url: "/tutorials/typescript/", count: 7, iconText: "TS", iconClass: "ss-topic-ts" },
            { label: "React", url: "/tutorials/react/", count: 14, iconText: "R", iconClass: "ss-topic-react" },
            { label: "Vue", url: "/tutorials/vue/", count: 6, iconText: "V", iconClass: "ss-topic-vue" },
            { label: "Performance", url: "/tutorials/performance/", count: 4, iconText: "P", iconClass: "ss-topic-perf" },
            { label: "Accessibility", url: "/tutorials/accessibility/", count: 5, iconText: "A", iconClass: "ss-topic-a11y" }
          ],
          cta: {
            label: "View all categories",
            detail: "See all 9 categories",
            url: "/tutorials/categories/"
          }
        },
        {
          title: "Popular Tutorials",
          type: "featuredList",
          items: [
            { label: "React Server Components Explained", url: "/tutorials/react-server-components-explained/", meta: "12 min read", thumbText: "R", thumbClass: "ss-thumb-react" },
            { label: "JavaScript Promises in Depth", url: "/tutorials/javascript-promises-in-depth/", meta: "10 min read", thumbText: "JS", thumbClass: "ss-thumb-js" },
            { label: "Modern CSS Layouts with Grid", url: "/tutorials/modern-css-layouts-with-grid/", meta: "9 min read", thumbText: "C", thumbClass: "ss-thumb-css" },
            { label: "TypeScript Basics for Beginners", url: "/tutorials/typescript-basics-for-beginners/", meta: "8 min read", thumbText: "TS", thumbClass: "ss-thumb-ts" },
            { label: "Vue 3 Composition API Guide", url: "/tutorials/vue-3-composition-api-guide/", meta: "11 min read", thumbText: "V", thumbClass: "ss-thumb-vue" }
          ]
        }
      ],
      cta: {
        label: "View all tutorials",
        url: "/tutorials/",
        variant: "green"
      }
    },
    {
      label: "Resources",
      url: "/resources/",
      hasMegaMenu: true,
      active: false,
      activePaths: ["/resources/"],
      columns: [
        {
          title: "Resource Types",
          type: "linkList",
          items: [
            { label: "Cheat Sheets", url: "/resources/cheat-sheets/" },
            { label: "Snippets", url: "/resources/snippets/" },
            { label: "Templates", url: "/resources/templates/" },
            { label: "Tools", url: "/resources/tools/" },
            { label: "Downloads", url: "/resources/downloads/" }
          ]
        },
        {
          title: "Popular Resources",
          type: "storyList",
          items: [
            { label: "CSS Grid Cheat Sheet", url: "/resources/css-grid-cheat-sheet/" },
            { label: "Accessibility Checklist", url: "/resources/accessibility-checklist/" },
            { label: "Design Token Starter Kit", url: "/resources/design-token-starter-kit/" },
            { label: "Component Library Template", url: "/resources/component-library-template/" },
            { label: "Front-End Interview Guide", url: "/resources/front-end-interview-guide/" }
          ]
        }
      ],
      cta: {
        label: "View all resources",
        url: "/resources/"
      }
    },
    {
      label: "Guides",
      url: "/guides/",
      hasMegaMenu: true,
      active: false,
      activePaths: ["/guides/"],
      columns: [
        {
          title: "Guide Topics",
          type: "linkList",
          items: [
            { label: "CSS", url: "/guides/css/" },
            { label: "JavaScript", url: "/guides/javascript/" },
            { label: "TypeScript", url: "/guides/typescript/" },
            { label: "React", url: "/guides/react/" },
            { label: "Accessibility", url: "/guides/accessibility/" },
            { label: "Performance", url: "/guides/performance/" },
            { label: "Design Systems", url: "/guides/design-systems/" }
          ]
        },
        {
          title: "Featured Guides",
          type: "storyList",
          items: [
            { label: "Complete CSS Grid Guide", url: "/guides/complete-css-grid-guide/" },
            { label: "Accessibility Handbook", url: "/guides/accessibility-handbook/" },
            { label: "React Performance Guide", url: "/guides/react-performance-guide/" },
            { label: "Design Systems Playbook", url: "/guides/design-systems-playbook/" },
            { label: "Front-End Architecture Guide", url: "/guides/front-end-architecture-guide/" }
          ]
        }
      ],
      cta: {
        label: "View all guides",
        url: "/guides/"
      }
    },
    {
      label: "About",
      url: "/about/",
      hasMegaMenu: false,
      active: false,
      activePaths: ["/about/"],
      columns: [],
      cta: null
    },
    {
      label: "Contact",
      url: "/contact/",
      hasMegaMenu: false,
      active: false,
      activePaths: ["/contact/"],
      columns: [],
      cta: null
    }
  ];

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

  function renderCta(cta) {
    if (!cta) {
      return null;
    }

    var ctaClass = "ss-panel-cta";
    if (cta.variant === "green") {
      ctaClass += " ss-panel-cta-green";
    }

    var ctaLink = createElement("a", ctaClass);
    ctaLink.href = toAbsoluteUrl(cta.url);

    var left = createElement("span");
    left.textContent = cta.label;

    if (cta.detail) {
      var detail = createElement("small", null, cta.detail);
      left.appendChild(detail);
    }

    ctaLink.appendChild(left);
    ctaLink.appendChild(createElement("span", null, "->"));

    return ctaLink;
  }

  function renderColumnList(column) {
    var listClass = "ss-mega-list";
    if (column.type === "storyList") {
      listClass = "ss-story-list";
    } else if (column.type === "categoryList") {
      listClass = "ss-category-list";
    } else if (column.type === "featuredList") {
      listClass = "ss-popular-list";
    }

    var list = createElement("ul", listClass);
    list.setAttribute("role", "list");

    (column.items || []).forEach(function (entry) {
      var item = createElement("li");
      var link = createElement("a");
      link.href = toAbsoluteUrl(entry.url);

      if (column.type === "categoryList") {
        if (entry.highlight) {
          link.classList.add("is-highlight");
        }

        var topic = createElement("span", "ss-topic");
        var icon = createElement("span", "ss-topic-icon " + (entry.iconClass || ""), entry.iconText || "");
        topic.appendChild(icon);
        topic.appendChild(document.createTextNode(entry.label));

        var count = createElement("span", "ss-count", String(entry.count || 0));
        link.appendChild(topic);
        link.appendChild(count);
      } else if (column.type === "featuredList") {
        var thumbClass = "ss-thumb" + (entry.thumbClass ? " " + entry.thumbClass : "");
        link.appendChild(createElement("span", thumbClass, entry.thumbText || ""));
        link.appendChild(createElement("span", "ss-copy", entry.label));
        link.appendChild(createElement("span", "ss-meta", entry.meta || ""));
      } else {
        link.textContent = entry.label;
      }

      item.appendChild(link);
      list.appendChild(item);
    });

    return list;
  }

  function renderMegaMenu() {
    if (!nav) {
      return;
    }

    var currentPath = normalizePath(window.location.pathname);

    megaMenuData.forEach(function (item) {
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
      if (item.hasMegaMenu) {
        liClass += " ss-nav-item-has-mega";
      }
      if (item.active) {
        liClass += " is-active";
      }

      var li = createElement("li", liClass);

      if (!item.hasMegaMenu) {
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
      trigger.appendChild(createElement("span", "ss-trigger-caret"));

      var panel = createElement("section", "ss-mega-panel");
      panel.id = panelId;
      panel.setAttribute("role", "region");
      panel.setAttribute("aria-label", item.label + " menu");
      panel.setAttribute("aria-hidden", "true");

      panel.appendChild(createElement("div", "ss-mega-pointer"));

      var contentClass = "ss-mega-content";
      if ((item.columns || []).length > 1) {
        contentClass += " ss-mega-two-col";
      }
      var content = createElement("div", contentClass);

      (item.columns || []).forEach(function (column) {
        var columnWrap = createElement("div", "ss-mega-column");
        columnWrap.appendChild(createElement("h2", "ss-mega-title", String(column.title || "").toUpperCase()));
        columnWrap.appendChild(renderColumnList(column));

        if (column.cta) {
          columnWrap.appendChild(renderCta(column.cta));
        }

        content.appendChild(columnWrap);
      });

      if (item.cta && content.lastElementChild) {
        content.lastElementChild.appendChild(renderCta(item.cta));
      }

      panel.appendChild(content);
      li.appendChild(trigger);
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
    var focusables = [trigger].concat(panelFocusables);

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
