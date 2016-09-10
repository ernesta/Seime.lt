<div class="clearfix" id="sittings">
	<span id="sitting-year-span">
		<select id="sitting-year">
			<option value="2008" selected="selected">2008</option>
			<option value="2009">2009</option>
			<option value="2010">2010</option>
			<option value="2011">2011</option>
			<option value="2012">2012</option>
			<option value="2013">2013</option>
			<option value="2014">2014</option>
			<option value="2015">2015</option>
			<option value="2016">2016</option>
		</select>
	</span>
	
	<span id="sitting-month-span">
		<select id="sitting-month">
			<option value="1" selected="selected">sausis</option>
			<option value="2">vasaris</option>
			<option value="3">kovas</option>
			<option value="4">balandis</option>
			<option value="5">gegužė</option>
			<option value="6">birželis</option>
			<option value="7">liepa</option>
			<option value="8">rugpjūtis</option>
			<option value="9">rugsėjis</option>
			<option value="10">spalis</option>
			<option value="11">lapkritis</option>
			<option value="12">gruodis</option>
		</select>
	</span>
	
	<script type="text/javascript">
		jQuery('#sitting-month option[value=' + (new Date().getMonth() + 1) + ']').attr('selected', 'selected');
		jQuery('#sitting-year option[value=' + new Date().getFullYear() + ']').attr('selected', 'selected');
		jQuery("#sitting-month").trigger("liszt:updated");
		jQuery("#sitting-year").trigger("liszt:updated");
	</script>
	
	<script type="text/javascript">
		$("#sitting-year").chosen({no_results_text: "Nerasta"});
		$("#sitting-month").chosen({no_results_text: "Nerasta"});
	</script> 
	
	<div id="calendar"></div>
</div>