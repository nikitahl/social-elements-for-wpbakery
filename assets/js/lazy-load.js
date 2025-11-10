(function($) {
  'use strict';

  /**
   * Platform-specific post-load handlers.
   * Add new platforms here if they need special processing after injection.
   */
  const platformHandlers = {
    facebook: function(container) {
      // Wait for Facebook SDK to be available, then parse
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

        // Copy inline script content if any
        if (oldScript.innerHTML) {
          newScript.innerHTML = oldScript.innerHTML;
        }

        // Append to document to trigger execution
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
      // No content to load - keep the placeholder with the link visible
      console.warn('No embed content found for lazy loading');
      return;
    }

    try {
      const content = atob(encodedContent);
      const platform = container.querySelector('.sefwpb-lazy-placeholder')?.getAttribute('data-platform');

      // Create a temporary container to parse the HTML
      const tempDiv = document.createElement('div');
      tempDiv.innerHTML = content;

      // Extract scripts for later execution
      const scripts = Array.from(tempDiv.querySelectorAll('script'));

      // Remove scripts from the content first
      scripts.forEach(function(script) {
        script.parentNode.removeChild(script);
      });

      // Inject the HTML (without scripts)
      container.innerHTML = tempDiv.innerHTML;
      container.classList.add('sefwpb-loaded');

      // Check if platform has a special handler
      if (platform && platformHandlers[platform]) {
        platformHandlers[platform](container);
      } else {
        // Default: execute scripts after DOM update
        executeScripts(scripts);
      }

    } catch (e) {
      console.error('Error loading lazy content:', e);
      // Don't replace the content - keep the fallback link visible
      // Just add an error class for styling if needed
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

    // Create intersection observer
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

    // Observe all containers
    containers.forEach(function(container) {
      observer.observe(container);
    });
  }

  // Initialize on DOM ready and on WPBakery reload
  $(document).ready(initLazyLoad);
  $(window).on('vc_reload', initLazyLoad);

})(jQuery);
