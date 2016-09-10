/* QuickSearch */
jQuery('input#list-search').quicksearch('.listMember a', {
	'hide': function () {
		jQuery(this).parent().addClass('hide-list-a');
	},
	'show': function () {
		jQuery(this).parent().removeClass('hide-list-a');
	}
});

/* Add/remove standard phrase from search box */
jQuery('*').focus(function() {	
  if (jQuery('#list-search').attr('value') == '') { jQuery('#list-search').attr('value','Seimo nario paieška'); }
});

jQuery('#list-search').focus(function() {
  if (jQuery(this).attr('value') == 'Seimo nario paieška') { jQuery(this).attr('value',''); }
});

jQuery('#list-search').focusout(function() {
  if (jQuery(this).attr('value') == '') { jQuery(this).attr('value','Seimo nario paieška'); }
});

/* Implement fraction limiting */
jQuery('#fraction-search').change(function() { limitByFraction(); });

function limitByFraction() {
	var classname = jQuery('#fraction-search option:selected').val();
	if (classname != 'all') {
		jQuery('.listMember').addClass('hide-list-b');
		jQuery('.' + classname).removeClass('hide-list-b');
	}
	else {
		jQuery('.listMember').removeClass('hide-list-b');
	}
}

/* Fix browser inconsistencies */
jQuery(document).ready(function() { 
	limitByFraction();
	jQuery("#fraction-search").trigger("liszt:updated");
	if (jQuery.browser == 'opera') {	setTimeout(function() { jQuery('#list-search').attr('value','Seimo nario paieška'); }, 1000); }
	else { jQuery('#list-search').attr('value','Seimo nario paieška'); }
});