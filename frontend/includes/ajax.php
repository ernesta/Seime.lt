<?php 

	if ((isset($_GET['year'])) && (isset($_GET['month']))) {
		require_once('includes/includes.php');
		Initialisator::initialise();
		//require_once('includes/DB.php');
		require_once('includes/template_functions.php');
			try { 
				//$db = new DB('mysql:dbname=aurimas_seime-lt;host=localhost', 'aurimas_seime-lt', '~3&fmls[$Pj(u`hU=Vaisz.A:,3)qvfd}xAXR-.MR5Mx:MVXNYL`M)A:c.b?DRWS', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
				$db = Initialisator::getDB();
				
				$year = $_GET['year'];
				$month = $_GET['month'];
				
				//get all the sittings for the given month, get into proper data structure				
				$data = $db->getArray('SELECT id, number, type, DAY(end_time) as day FROM sittings WHERE YEAR(end_time) = ? AND MONTH(end_time) = ? ORDER by end_time ASC', 
					array($year, sprintf('%02d', $month)));
				
				$sittings = array();
				foreach ($data as $sitting) {	
					$sittings[$sitting['day']][] = $sitting; 
				}	
					
				// get start_date and last_date for the given month
				$start_day_of_week = date('N', strtotime("$year-$month-1"));
				$end_day = date('d', mktime(0, 0, 0, $month + 1, 0, $year));
				// create a calendar
				echo '<table id=sitting-calendar>';
				echo '<tr><th>Pirmadienis</th><th>Antradienis</th><th>Trečiadienis</th><th>Ketvirtadienis</th><th>Penktadienis</th><th>Šeštadienis</th><th>Sekmadienis</th></tr>';
				//create the preceeding days of week
				echo '<tr>';
				for ($i = 1; $i < $start_day_of_week; $i++) {
					echo '<td class="disabled"></td>';
				}				
				//the main loop
				$current_week_day = $i - 1;
				for ($i = 1; $i <= $end_day; $i++) {
					$current_week_day++;
					if ($current_week_day == 8) {
						$current_week_day = 1;
						echo '</tr><tr>';
					}
					echo '<td>';
					echo '<span>' . $i . '</span>';
					if (isset($sittings[$i])) {
						foreach ($sittings[$i] as $sitting) {
							$type = str_replace(' neeilinis', ' n.', ucfirst($sitting['type']));
							echo '<a href="' . getSittingLink($sitting['id'])  . '">' . $type  . '</a>';
						}
					}
					
					echo '</td>';
				}
				//finish last week
				for ($i = $current_week_day + 1; $i < 8; $i++) {
					echo '<td class="disabled end"></td>';
				}
				echo '</tr>';
				echo '</table>';
			}
			catch (Exception $e) {
				echo 'Įvyko klaida prisijungiant prie duomenų bazės';
			}
	}

?>
