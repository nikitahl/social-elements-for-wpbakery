(function($) {
  'use strict';

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

  /**
   * Load the actual content
   */
  function loadContent(container) {
    const encodedContent = container.getAttribute('data-content');

    if (!encodedContent) {
      return;
    }

    try {
      const content = atob(encodedContent);
      container.innerHTML = content;
      container.classList.add('sefwpb-loaded');
    } catch (e) {
      console.error('Error loading lazy content:', e);
    }
  }

  // Initialize when DOM is ready
  $(document).ready(function() {
    initLazyLoad();
  });

})(jQuery);
