/*
 * List Box - jQuery plugin 1.0.0
 *
 * Copyright (c) 2009 Daniel Cole
 * http://dan-cole.com/list-box
 *
 * Dual licensed under the MIT and GPL licenses:
 *	 http://www.opensource.org/licenses/mit-license.php
 *	 http://www.gnu.org/licenses/gpl.html
 *
 */

jQuery.fn.listbox = function() {
	return this.each(function(){
		listbox_seperator = "; ";
		jQuery(this).css("display", "none");
		var value = jQuery(this).val();
		var items = value.split(listbox_seperator);
		var list = "";
		jQuery.each(items, function() {
			list = list + "<li class='listbox-item'>" + this + " <span>x</span></li>";
		});
		jQuery(this).after("<div class='listbox'><ul>" + list + "<li class='listbox-input'><input type='text' autocomplete='off'></input></li></ul></div>");
	});
};

jQuery(document).ready(function(){
	jQuery(".listbox ul li").live("click", function(){
		jQuery(".listbox ul li").removeClass("listbox-focus");
		jQuery(this).addClass("listbox-focus");
	});
	jQuery(".listbox-item span").live("click", function(){
		jQuery(this).parent().remove();
	});
	jQuery(document).keypress(function(e){
		var tag = jQuery(".listbox-input input").val();
		if (e.keyCode == 8 || e.keyCode == 46) {
			if (!jQuery(".listbox-focus").hasClass("listbox-input")) {
				jQuery(".listbox-focus").addClass("listbox-delete");
				if (jQuery(".listbox-focus").prev().hasClass("listbox-item")) {
					jQuery(".listbox-focus").prev().addClass("listbox-focus");
				}
				else {
					jQuery(".listbox-focus").next().addClass("listbox-focus");
				}
				jQuery(".listbox-delete").remove();
			}
		}
		else if (e.keyCode == 37 && tag == "") {
			if (jQuery(".listbox-focus").prev().hasClass("listbox-item")) {
				jQuery(".listbox ul li").removeClass("listbox-unfocus");
				jQuery(".listbox-focus").addClass("listbox-unfocus");
				jQuery(".listbox-unfocus").removeClass("listbox-focus");
				jQuery(".listbox-unfocus").prev().addClass("listbox-focus");
			}
		}
		else if (e.keyCode == 39 && tag == "") {
			if ((jQuery(".listbox-focus").next().hasClass("listbox-item")) || (jQuery(".listbox-focus").next().hasClass("listbox-input"))) {
				jQuery(".listbox ul li").removeClass("listbox-unfocus");
				jQuery(".listbox-focus").addClass("listbox-unfocus");
				jQuery(".listbox-unfocus").removeClass("listbox-focus");
				jQuery(".listbox-unfocus").next().addClass("listbox-focus");
			}
			if (jQuery(".listbox-focus").next().hasClass("listbox-input")) {
				jQuery(".listbox-input input").focus();
			}
		}
	});
	jQuery(".listbox-input input").live("keypress", function(e){
		listbox_seperator = "; ";
		if (jQuery(".listbox-focus").hasClass("listbox-input")) {
			var tag = jQuery(this).val();
			if (e.keyCode == 13) {
				e.preventDefault();
				jQuery(this).parent().before('<li class="listbox-item">' + tag + ' <span>x</span></li>');
				jQuery(this).val("");
				var value = jQuery(this).parent().parent().parent().prev().val();
				jQuery(this).parent().parent().parent().prev().val(value + listbox_seperator + tag);
			}
		}
		else {
			e.preventDefault();
		}
	});
});
