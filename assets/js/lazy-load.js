(function($) {
  'use strict';

  /**
   * Platform-specific post-load handlers.
   * Add new platforms here if they need special processing after injection.
   */
  const platformHandlers = {
    facebook: function(container) {
      var checkFB = function() {
        if (typeof FB !== 'undefined' && FB.XFBML && FB.XFBML.parse) {
          FB.XFBML.parse(container);
        } else {
          setTimeout(checkFB, 100);
        }
      };
      setTimeout(checkFB, 50);
    }
  };

  /**
   * Register a platform-specific post-load handler (used by add-ons, e.g. Pro).
   *
   * @param {string}   platform - Platform name matching the placeholder data-platform.
   * @param {Function} handler  - Callback receiving the element the embed was loaded into.
   */
  window.sefwpbRegisterLazyLoadHandler = function(platform, handler) {
    if (typeof platform === 'string' && typeof handler === 'function') {
      platformHandlers[platform] = handler;
    }
  };

  /**
   * Execute scripts from the embed HTML.
   *
   * @param {Array} scripts - Array of script elements to execute.
   */
  function executeScripts(scripts) {
    setTimeout(function() {
      scripts.forEach(function(oldScript) {
        const newScript = document.createElement('script');

        // Copy attributes
        Array.from(oldScript.attributes).forEach(function(attr) {
          newScript.setAttribute(attr.name, attr.value);
        });

        if (oldScript.innerHTML) {
          newScript.innerHTML = oldScript.innerHTML;
        }

        document.body.appendChild(newScript);
      });
    }, 50);
  }

  /**
   * Load the actual content for a given container.
   */
  function loadContent(container) {
    const encodedContent = container.getAttribute('data-content');

    if (!encodedContent) {
      console.warn('No embed content found for lazy loading');
      return;
    }

    try {
      const content = atob(encodedContent);
      const platform = container.querySelector('.sefwpb-lazy-placeholder')?.getAttribute('data-platform');

      // Temporary container to parse the HTML
      const tempDiv = document.createElement('div');
      tempDiv.innerHTML = content;

      const scripts = Array.from(tempDiv.querySelectorAll('script'));

      scripts.forEach(function(script) {
        script.parentNode.removeChild(script);
      });

      // Unwrap the lazy container so the embed markup matches the non-lazy output
      // and element styles (e.g. "> div", "iframe" selectors) apply the same way.
      const parent = container.parentNode;

      while (tempDiv.firstChild) {
        parent.insertBefore(tempDiv.firstChild, container);
      }

      parent.removeChild(container);

      if (platform && platformHandlers[platform]) {
        platformHandlers[platform](parent);
      } else {
        executeScripts(scripts);
      }

    } catch (e) {
      console.error('Error loading lazy content:', e);
      container.classList.add('sefwpb-lazy-error');
    }
  }

  /**
   * Initialize lazy loading
   */
  function initLazyLoad() {
    const containers = document.querySelectorAll('.sefwpb-lazy-container');

    if (!containers.length) {
      return;
    }

    const observer = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          loadContent(entry.target);
          observer.unobserve(entry.target);
        }
      });
    }, {
      rootMargin: '300px'
    });

    containers.forEach(function(container) {
      observer.observe(container);
    });
  }

  // Initialize on DOM ready and on WPBakery reload
  $(document).ready(initLazyLoad);
  $(window).on('vc_reload', initLazyLoad);

})(jQuery);
