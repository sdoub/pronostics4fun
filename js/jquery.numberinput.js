(function($){
	$.fn.numberInput = function() {
		return this.each(function() {
			$(this).keydown(function(event){
				$.log(event.keyCode);
				return KEYS_ALLOWED[event.keyCode] ? true : false;
			});
		});
	};
	var KEYS_ALLOWED = {
		   8 : 'BACKSPACE'
		,  9: 'TAB'
		, 13 : 'ENTER'
		, 37 : 'LEFT_ARROW'
		, 39 : 'RIGHT_ARROW'
		, 46 : 'DELETE'
		, 48 : 'ZERO'
		, 49 : 'ONE'
		, 50 : 'TWO'
		, 51 : 'THREE'
		, 52 : 'FOUR'
		, 53 : 'FIVE'
		, 54 : 'SIX'
		, 55 : 'SEVEN'
		, 56 : 'EIGHT'
		, 57 : 'NINE'
		, 96 : 'ZEROPAD'
		, 97 : 'ONEPAD'
		, 98 : 'TWOPAD'
		, 99 : 'THREEPAD'
		,100 : 'FOURPAD'
		,101 : 'FIVEPAD'
		,102 : 'SIXPAD'
		,103 : 'SEVENPAD'
		,104 : 'EIGHTPAD'
		,105 : 'NINEPAD'			
	};
})(jQuery);