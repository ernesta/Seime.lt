<div id="search">
	<span id="list-search-span">
		<input id="list-search" type="text" />
	</span>
	
	<select id="fraction-search" data-placeholder="Frakcijos pasirinkimas" >
		<option value="all">Visos frakcijos</option>
		<?php
			foreach(getRawFractionList() as $abbr => $name) {
				echo '<option value="' . $abbr . '">' . $name . '</option>';
			}
		?>
	</select>
</div>