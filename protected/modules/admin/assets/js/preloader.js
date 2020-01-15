/**
 * Created by miloslawsky on 22.10.16.
 */

(function($){
    var __preloader = function(){
        this.hideAll = function(){
            $('.esd-loader-wrapper').hide();
        };
        this.showAll = function(){
            $('.esd-loader-wrapper').hide();
        };
    };
    esd.addPlugin('preloader', new __preloader());
})(jQuery);