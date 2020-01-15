/**
 * @name JQeury TileSlideMenu plugin file 
 * @author Artur Oliveira - artur.oliveira@gmail.com - 2012-06-02
 * @version 1.0
 *
 */
/*
 $(document).ready(function(){

           jQuery.event.add(window, 'load', resizeFrame);
           jQuery.event.add(window, 'resize', resizeFrame);

            function resizeFrame(){
                $("div.TileSlideMenu").width($("div.TileSlideMenu").parent().width()-50);
            }
            resizeFrame();
});
*/
(function ($) {
	$.fn.TileSlideMenu = function (method) {
		var ulPadding = 15;
		var items = $('#' + this.attr('id') + '_items');
		$(items.css({overflow: 'hidden'}));
		var lastLi = items.children().find('li:last-child');
		inputSelector = '#' + $(items.attr('id'));
		items.mousemove(function(e){
            //Get menu width
            var divWidth = items.width();
            //As images are loaded ul width increases,
            //so we recalculate it each time
            var ulWidth = lastLi[0].offsetLeft + lastLi.outerWidth() + ulPadding;
            var left = (e.pageX - items.offset().left) * (ulWidth-divWidth) / divWidth;
            items.scrollLeft(left);
		});
		return false;
	};
})(jQuery);
			
/*
jQuery(function(){
        //Get our elements for faster access and set overlay width
        var div = $('div.tile-slide-menu-items'),
                ul = $('ul.tile-slide-menu-item'),
                ulPadding = 15;


        //Remove scrollbars
        div.css({overflow: 'hidden'});

        //Find last image container
        var lastLi = ul.find('li:last-child');

        //When user move mouse over menu
        div.mousemove(function(e){
                //Get menu width
                var divWidth = div.width();
                //As images are loaded ul width increases,
                //so we recalculate it each time
                var ulWidth = lastLi[0].offsetLeft + lastLi.outerWidth() + ulPadding;
                var left = (e.pageX - div.offset().left) * (ulWidth-divWidth) / divWidth;
                div.scrollLeft(left);
        });
});
*/