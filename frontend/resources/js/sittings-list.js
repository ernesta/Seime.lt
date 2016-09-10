/* on selection of year or month, show the calendar */

jQuery(document).ready(function() {
	if ((jQuery('#sitting-year option:selected').val() == '2008') && (jQuery('#sitting-month option:selected').val() == '1')) {	
		jQuery('#sitting-month option[value=' + (new Date().getMonth() + 1) + ']').attr('selected', 'selected');
		jQuery('#sitting-year option[value=' + new Date().getFullYear() + ']').attr('selected', 'selected');
	}
	showCalendar();
});

jQuery('#sitting-year, #sitting-month').change(function() {
	showCalendar();
	/* fix the fact that not all years have all the months */
	if (this.id == 'sitting-year') {
		//alert(AllowedSittingsMonths);
	}
});

/* displaying calendar */

function showCalendar() {
	if (typeof currentCalendarAjax != 'undefined') { currentCalendarAjax = null; }
	var year = jQuery('#sitting-year option:selected').val();
	var month = jQuery('#sitting-month option:selected').val();
	jQuery("#sitting-month").trigger("liszt:updated");
	jQuery("#sitting-year").trigger("liszt:updated");    
	currentCalendarAjax = jQuery.ajax({
  	url: 'ajax.php',
  	data: 'year=' + year + '&month=' + month,
 		success: function(data) { 
			jQuery('#sitting-month option[value=' + currentCalendarAjax.month + ']').attr('selected', 'selected');
			jQuery('#sitting-year option[value=' + currentCalendarAjax.year + ']').attr('selected', 'selected');
			jQuery("#sitting-month").trigger("liszt:updated");
			jQuery("#sitting-year").trigger("liszt:updated");
    	jQuery('#calendar').html(data);
  	}
	});
	currentCalendarAjax.year = year;
	currentCalendarAjax.month = month;
}


