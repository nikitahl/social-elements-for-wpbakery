function sefwpbLoadTwitterEmbed(context) {
	(context || document).querySelectorAll('.sefwpb-twitter-embed-container[data-is-rendered="false"]').forEach(function(container) {
		var url = container.getAttribute('data-tweet-url');
		var theme = container.getAttribute('data-theme') || 'light';
		if (!url) return;

		var callbackName = 'twitterEmbedCallback_' + Math.random().toString(36).substr(2, 9);
		window[callbackName] = function(data) {
			container.innerHTML = data.html;
			container.setAttribute('data-is-rendered', 'true');
			if (window.twttr && window.twttr.widgets && typeof window.twttr.widgets.load === 'function') {
				window.twttr.widgets.load(container);
			}
			delete window[callbackName];
		};

		var script = document.createElement('script');
		script.src = 'https://publish.twitter.com/oembed?url=' + encodeURIComponent(url) + '&theme=' + theme + '&callback=' + callbackName;
		document.head.appendChild(script);
	});
}

document.addEventListener('DOMContentLoaded', function() {
	sefwpbLoadTwitterEmbed();
});

// If you add new containers dynamically, call:
// renderTwitterEmbeds(); // or renderTwitterEmbeds(parentElement);
