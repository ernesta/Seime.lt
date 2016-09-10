<?php
	class SittingCharts extends Charts {
		public function __construct(DB $db, SeimeLT $parent, $id) {
			$this->DB = $db;
			$this->parent = $parent;
			$this->id = $id;
		
			// Collect general data
			$sitting_data = $this->DB->getArray('SELECT type, DATE(sittings.end_time) as date,
				TIME_TO_SEC(TIMEDIFF(MAX(questions.end_time), MIN(questions.start_time))) as total_length,
				MIN(questions.start_time) as start_time
				FROM sittings JOIN questions ON sittings_id = sittings.id
				WHERE sittings.id = ? GROUP BY sittings_id', array($id));
		
			if (empty($sitting_data)) throw new Exception('No sitting with ID found');
			else {
				setlocale(LC_TIME, 'lt_LT.UTF8');
				mb_internal_encoding('UTF-8');
				$this->sitting_data = $sitting_data[0];		
				$this->sitting_data['start_time'] = strtotime($this->sitting_data['start_time']);	
				$date = strftime('%Y %B %e', strtotime($this->sitting_data['date']));
				define('SITTING_DATE', $date);
			}		
		}
	
		public function SittingCountOverTime($type, $count_var, $count_labels_var) {
			// Get the data	
			$data = $this->DB->getArray(
				'SELECT SUM(subquestions_participation.presence) as count, TIME_FORMAT(TIME(subquestions.start_time), "%H:%i") AS start_time
				FROM `subquestions_participation`
				JOIN subquestions ON subquestions.id = subquestions_id
				JOIN questions ON questions.id = questions_id
				JOIN sittings ON sittings.id = sittings_id
				WHERE TIMEDIFF(subquestions.end_time, subquestions.start_time) >0 AND sittings.id = ?
				GROUP BY subquestions_participation.subquestions_id
				ORDER BY subquestions.start_time ASC', array($this->id));
	
			$labels = array();
			$counts = array();

			foreach ($data as $row) {
				$labels[] = $row['start_time'];			
				$counts[] = (int) $row['count'];	
			}
	
			$this->registerJS('stats-attendance', $type . '_' . $count_var, $counts);
			$this->registerJS('stats-attendance', $type . '_' . $count_labels_var, (object) $labels);
		}

		public function SittingDynamics($type, $series_var, $members_var, $labels_var) {
			if (getSittingImage($this->id) !== false) return;

			$sitting_data = $this->sitting_data;
			$total_length = $sitting_data['total_length'];
		
			// Get the in-out dynamics
			$data = $this->DB->getArray(
				'SELECT members.name, members_id, subquestions_participation.presence, TIME(subquestions.start_time) as start_time, TIME(subquestions.end_time) as end_time,
				TIME_TO_SEC(TIMEDIFF(subquestions.end_time, subquestions.start_time)) as length
				FROM `subquestions_participation` JOIN subquestions ON subquestions.id = subquestions_id
				JOIN questions ON questions.id = questions_id
				JOIN members ON members_id = members.id
				JOIN sittings ON sittings.id = sittings_id
				WHERE TIMEDIFF(subquestions.end_time, subquestions.start_time) > 0
				AND sittings.id = ?					
				ORDER BY subquestions.start_time, members.name ASC', array($this->id));

			// Parse the dynamics
			$i = 0;
			$start_time = false;
			$series = array();
			$members = array();
			$values = array();

			foreach ($data as $row) {
				if ($row['start_time'] !== $start_time) {
					if ($start_time !== false) {
						// Save previous data, if any
						$present['data'] = array_values($present['data']);
						$not_present['data'] = array_values($not_present['data']);
						$series[] = $present;
						$series[] = $not_present;
					}
				
					// Reset current interval data
					$start_time = $row['start_time'];
					$present = array('name' => 'Buvo ' . $row['start_time'] . ' - ' . $row['end_time'], 'data' => array(), 'color' => '#C78933');
					$not_present = array('name' => 'Nebuvo ' . $row['start_time'] . ' - ' . $row['end_time'], 'data' => array(), 'color' => '#D6CEAA');
				}
			
			// Actual parsing
				$value = round($row['length'] / $total_length * 100, 2);
				$values[$start_time] = $value;
				$members[$row['members_id']] = $row['name'];
			
				if ($row['presence'] == 0) {
					$present['data'][$row['members_id']] = 0;
					$not_present['data'][$row['members_id']] = $value;
				} else {
					$present['data'][$row['members_id']] = $value;
					$not_present['data'][$row['members_id']] = 0;
				}
			}
		
			$present['data'] = array_values($present['data']);
			$not_present['data'] = array_values($not_present['data']);
			$series[] = $present;
			$series[] = $not_present;
			
			//Generate labels 
			$labels = array();
			
			for ($i = 0; $i <= 100; $i += 10) {
				$labels[$i] = date('H:i', $sitting_data['start_time'] + $total_length / 100 * $i);	
			}
					
			$this->registerJS('full-attendance', $type . '_' . $members_var, array_values($members));
			$this->registerJS('full-attendance', $type . '_' . $labels_var, $labels);
			$this->registerJS('full-attendance', $type . '_' . $series_var, array_reverse($series));
		}

		protected function registerJS($file, $name, $data) {
			$this->parent->registerJS($file, 'js/charts/', $name, $data);
		}
	}