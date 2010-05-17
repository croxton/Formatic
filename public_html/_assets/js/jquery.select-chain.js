// Based on: http://remysharp.com/2007/09/18/auto-populate-multiple-select-boxes/
// Adapted by Mark Croxton for Formatic

(function ($) {
    $.fn.selectChain = function (options) {
        var defaults = {
            key: 	  "id",
            value: 	  "label",
			chosen:   "chosen"
        };
        
        var settings = $.extend({}, defaults, options);
        
        if (!(settings.target instanceof $)) settings.target = $(settings.target);
        
        return this.each(function () {	
			obj = this;	
            $(obj).change(function () {
                populate(obj);
            });
			$(document).ready(function(){
				populate(obj);
			});
        });

		function populate(obj) {
			
			var $$ = $(obj);
			
			var data = null;
            if (typeof settings.data == 'string') {
                data = settings.data + '&' + obj.name + '=' + $$.val();
            } else if (typeof settings.data == 'object') {
                data = settings.data;
                data[obj.name] = $$.val();
            }
			data['target'] = settings.target.attr('name');
			
            settings.target.empty();
            
            $.ajax({
                url: settings.url,
                data: data,
                type: (settings.type || 'post'),
                dataType: 'json',
                success: function (j) {

                    var options = [], i = 0, o = null;

                    for (i = 0; i < j.length; i++) {
                        // required to get around IE bug (http://support.microsoft.com/?scid=kb%3Ben-us%3B276228)
                        o = document.createElement("OPTION");
                        o.value = typeof j[i] == 'object' ? j[i][settings.key] : j[i];
                        o.text = typeof j[i] == 'object' ? j[i][settings.value] : j[i];

						settings.target.get(0).options[i] = o;
                    }
					
					// which options need to be selected?
					chosen_selector = '';
					$.each(settings.chosen, function(index, value) {
						chosen_selector += "option[value='"+value+"']";
						if (index < settings.chosen.length) {
							chosen_selector +=",";
						}
					});

					// hand control back to browser for a moment
					setTimeout(function () {
   						settings.target
						.find(chosen_selector)
						.attr('selected', 'selected')
                        .parent('select')
                        .trigger('change');
					}, 0);
                },
                error: function (xhr, desc, er) {
                    // add whatever debug you want here.
					alert("an error occurred");
                }
            });
		}
    };
})(jQuery);
