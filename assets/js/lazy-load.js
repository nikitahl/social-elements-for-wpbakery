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

      container.innerHTML = tempDiv.innerHTML;
      container.classList.add('sefwpb-loaded');

      if (platform && platformHandlers[platform]) {
        platformHandlers[platform](container);
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
