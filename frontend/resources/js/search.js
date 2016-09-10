var PLACEHOLDER = "Seimo nario paieška";


// Selecting
$("#fraction-search").chosen();

jQuery("#fraction-search").change(function() {
	filterByFraction();
});

function filterByFraction() {
	var className = jQuery("#fraction-search option:selected").val();
	
	if (className == "all") {
		jQuery(".listMember").removeClass("hide-a");
	} else {
		jQuery(".listMember").addClass("hide-a");
		jQuery("." + className).removeClass("hide-a");
	}
}


// Searching
$("input#list-search").quicksearch(".listMember a");

jQuery("input#list-search").quicksearch(".listMember a", {
	"hide": function () {
		jQuery(this).parent().parent().addClass("hide-b");
	},
	"show": function () {
		jQuery(this).parent().parent().removeClass("hide-b");
	}
});

// Deal with the placeholder
jQuery("*").focus(function() {	
	if (jQuery("#list-search").attr("value") == "") {
  		jQuery("#list-search").attr("value", PLACEHOLDER);
	}
});

jQuery("#list-search").focus(function() {
	if (jQuery(this).attr("value") == PLACEHOLDER) {
		jQuery(this).attr("value", "");
	}
});

jQuery("#list-search").focusout(function() {
	if (jQuery(this).attr("value") == "") {
		jQuery(this).attr("value", PLACEHOLDER);
	}
});

// Fix browser inconsistencies 
jQuery(document).ready(function() { 
	filterByFraction();
	jQuery("#fraction-search").trigger("liszt:updated");
	
	if (jQuery.browser == "opera") {
		setTimeout(function() {
			jQuery("#list-search").attr("value",PLACEHOLDER);
		}, 1000);
	} else {
		jQuery("#list-search").attr("value",PLACEHOLDER);
	}
});