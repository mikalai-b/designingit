/**
 *
 * jQuery Sort Highlighter Plugin
 */

;(function($) {
    'use strict';

    $.fn.sortHighlighter = function(sortField, sortDirection) {
		$(this).find('.'+sortField).addClass('sorted sorted-'+sortDirection);
		$(this).find('th.'+sortField).append('<span class="sort-direction-cta"></span>');
		$('.sort-direction-cta').click(function(){
			var url = $(this).siblings('a').attr('href');
			if (url.match(/sortDirection=asc/)) {
				url = url.replace(/sortDirection=asc/, 'sortDirection=desc');
			} else {
				url = url.replace(/sortDirection=desc/, 'sortDirection=asc');
			}
			window.location.href = url;
		});
    };
    
})(jQuery);
