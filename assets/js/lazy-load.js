(function($) {
  'use strict';

  /**
   * Load the actual content for a given container.
   */
  function loadContent(container) {
    const encodedContent = container.getAttribute('data-content');

    if (!encodedContent) {
      const error = container.querySelector('.sefwpb-lazy-placeholder')?.getAttribute('data-error');
      container.innerHTML = `<div class="sefwpb-lazy-error">${error || 'Embed data missing.'}</div>`;
      return;
    }

    try {
      const content = atob(encodedContent);

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

      // Now execute the scripts after DOM update
      // Use setTimeout to ensure DOM is fully updated
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

    } catch (e) {
      console.error('Error loading lazy content:', e);
      container.innerHTML = '<div class="sefwpb-lazy-error">Failed to load embed content.</div>';
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
      rootMargin: '100px'
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
