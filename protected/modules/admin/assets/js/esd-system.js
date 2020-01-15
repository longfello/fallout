/**
 * Created by miloslawsky on 22.10.16.
 */

(function($){
    var __esd = function(){
        this.plugins = {};

        this.init = function(){

        };

        this.addPlugin = function(name, content){
            self.plugins[name] = content;
            Object.defineProperty(this, name, {
                get: function() {
                    return self.plugins[name];
                }
            });
        };

        var self = this;
        this.init();
    };

    window.esd = new __esd();
})(jQuery);