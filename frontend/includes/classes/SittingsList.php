<?php 
	class SittingsList {
		public function __construct(PDO $DB) {
			$this->db = $DB;
		}
	
		public function populateAll() {
			$data = $this->db->getArray('SELECT DISTINCT CONCAT(YEAR(end_time), "-" ,MONTH(end_time)) as a FROM `sittings` WHERE end_time > 0', array());
			$dates = array();
	
			foreach ($data as $array) {
				$dates[$array['a']] = 1;
			}		
			
			echo '<script type="text/javascript">var AllowedSittingsMonths = ' . json_encode($dates)  . ';</script>' . "\n"; //json_encode($dates)
			echo '<script type="text/javascript" src="frontend/resources/js/sittings-list.js"></script>';
		}
	}