(function($) {
	$.fn.ellipsis = function(enableUpdating) {
		var s = document.documentElement.style;
		var applyTextOverFlow = true;
		if ($.browser.msie)
			applyTextOverFlow = false;
		if (applyTextOverFlow || ('textoverflow' in s || 'OTextOverflow' in s)) {
			return this.each(function() {
				var el = $(this);
				if (el.css("overflow") == "hidden") {
					var originalText = el.html();
					var w = el.parent().width();
					if (el.attr('displayWidth'))
						w = el.attr('displayWidth');

					var t = $(this.cloneNode(true)).hide().css( {
						'position' : 'absolute',
						'width' : 'auto',
						'overflow' : 'visible',
						'max-width' : 'inherit'
					});
					el.after(t);

					var text = originalText;
					while (text.length > 0 && t.width() > w) {
						text = text.substr(0, text.length - 1);
						t.html(text + "...");
					}

					el.html(t.html());
					t.remove();

					if (enableUpdating == true) {
						var oldW = el.width();
						setInterval(function() {
							if (el.width() != oldW) {
								oldW = el.width();
								el.html(originalText);
								el.ellipsis();
							}
						}, 200);
					}
				}
			});
		} else
			return this;
	};
})(jQuery);