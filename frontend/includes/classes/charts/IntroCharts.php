<?php
	class IntroCharts extends Charts {
		public function SittingParticipationExtract($type, $present_var, $not_present_var, $name_var) {
			// Select random member
			$i = rand(0, 140);

			$meta = $this->DB->getArray("SELECT id, name FROM members WHERE cadency_end = ? LIMIT $i,1 ", array('0000-00-00'));
			
			$id = $meta[0]['id'];
			$this->registerJS('intro-charts', $type . '_' . $name_var, $meta[0]['name']);

			// Get member data
			$data = $this->DB->getArray(
				'SELECT DATE_FORMAT(sittings.end_time, "%Y-%m-%d") as date,
					sittings.end_time, SUM(hours_present) as present,
					SUM(hours_available) - SUM(hours_present) as not_present FROM participation_data
					JOIN sittings ON sittings.id = sittings_id
					WHERE members_id = ? AND end_time != 0
					GROUP by date ORDER by date desc LIMIT 3', array($id));

			$present = array();
			$not_present = array();

			foreach ($data as $sitting) {
				$time = strtotime($sitting['end_time']) * 1000;
				$present[] =  array($time, (float) $sitting['present']);
				$not_present[] =  array($time, (float) $sitting['not_present']);
			}

			$this->registerJS('intro-charts', $type . '_' . $present_var, $present);
			$this->registerJS('intro-charts', $type . '_' . $not_present_var, $not_present);
		}
	
		public function RandomSittingOverTime($type, $count_var, $count_labels_var) {
			$details = $this->DB->getArray('SELECT max(id) as id, max(end_time) as time FROM sittings WHERE end_time > 0', array());
			$id = $details[0]['id'];
			$time = $details[0]['time'];
	
			// Get the data	
			$data = $this->DB->getArray(
				'SELECT SUM(subquestions_participation.presence) as count, TIME_FORMAT(TIME(subquestions.start_time), "%H:%i") AS start_time
				FROM `subquestions_participation`
				JOIN subquestions ON subquestions.id = subquestions_id
				JOIN questions ON questions.id = questions_id
				JOIN sittings ON sittings.id = sittings_id
				WHERE TIMEDIFF(subquestions.end_time, subquestions.start_time) >0 AND sittings.id = ?
				GROUP BY subquestions_participation.subquestions_id
				ORDER BY subquestions.start_time ASC', array($id));
	
			$labels = array();
			$counts = array();

			foreach ($data as $row) {
				$labels[] = (object) array('time' => $row['start_time'], 'unix' => strtotime($time) * 1000);
				$counts[] = (int) $row['count'];	
			}
	
			$this->registerJS('intro-sitting', $type . '_' . $count_var, $counts);
			$this->registerJS('intro-sitting', $type . '_' . $count_labels_var, (object) $labels);
		}

		protected function registerJS($file, $name, $data) {
			$this->parent->registerJS($file, 'js/charts/', $name, $data);
		}
	}