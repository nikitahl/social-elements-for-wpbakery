(function() {
	function injectRedditScript() {
		if (!document.getElementById('sefwpb-reddit-embed-script')) {
			var s = document.createElement('script');
			s.id = 'sefwpb-reddit-embed-script';
			s.src = 'https://embed.reddit.com/widgets.js';
			document.body.appendChild(s);
			return true;
		}
		return false;
	}

	function checkForRedditEmbed(mutationObserver) {
		try {
			if (document.querySelector('.reddit-embed-bq')) {
				if (injectRedditScript() && mutationObserver) {
					mutationObserver.disconnect();
				}
			}
		} catch (e) {
			console.error('Error checking for Reddit embed:', e);
		}
	}

	// Initial check
	var observer = new MutationObserver(function() {
		checkForRedditEmbed(observer);
	});
	checkForRedditEmbed(observer);
	observer.observe(document.body, { childList: true, subtree: true });
})();
