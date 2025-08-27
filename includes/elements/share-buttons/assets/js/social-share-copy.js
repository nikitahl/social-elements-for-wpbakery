document.addEventListener("DOMContentLoaded", function() {
	function copyToClipboard(text) {
		if (navigator.clipboard && window.isSecureContext) {
			return navigator.clipboard.writeText(text);
		} else {
			let textArea = document.createElement("textarea");
			textArea.value = text;
			textArea.style.position = "fixed";
			textArea.style.left = "-999999px";
			textArea.style.top = "-999999px";
			document.body.appendChild(textArea);
			textArea.focus();
			textArea.select();
			return new Promise((res, rej) => {
				document.execCommand("copy") ? res() : rej();
				textArea.remove();
			});
		}
	}
	document.querySelectorAll(".sefwpb-social-share__button--copy").forEach(function(button) {
		button.addEventListener("click", function(e) {
			e.preventDefault();
			const link = button.getAttribute("data-link");
			copyToClipboard(link).then(function() {
				button.querySelector("span").innerText = button.getAttribute("data-copied-label");
				setTimeout(function() {
					button.querySelector("span").innerText = button.getAttribute("data-copy-label");
				}, 1500);
			}, function() {
				alert(button.getAttribute("data-fail-label") + " " + link);
			});
		});
	});
});
