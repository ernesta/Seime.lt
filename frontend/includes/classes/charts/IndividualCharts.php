<?php
	class IndividualCharts extends Charts {
		public function __construct(DB $db, SeimeLT $parent, $id) {
			parent::__construct($db, $parent);
			$data = $this->DB->getArray('SELECT name, fraction from members WHERE id = ?', array($id));
			
			$this->id = $id;
			$this->name = $data[0]['name'];
			$this->fraction = $data[0]['fraction'];
			$this->registerJS('IndividualName', $this->name);
			$this->registerJS('IndividualFraction', $this->fraction);
		}
	
		public function IndividualPieChart($type, $indv_var, $parl_var) {
			$base_sql = '	SELECT DISTINCT vote, count(vote) as count 
						FROM votes 
						JOIN actions 
							ON actions.id = actions_id
						JOIN questions
							ON questions.id = questions_id
						JOIN sittings 
							ON sittings_id = sittings.id
						WHERE %s  
						GROUP BY vote';
		
			$indv_sql = sprintf($base_sql, self::cw('members_id = ? AND vote != ?'));
			$indv_totals = $this->getPieData($indv_sql, array($this->id, 'not presen'));
			$this->registerJS($type . '_' . $indv_var, $indv_totals);
		
			$parl_sql = sprintf($base_sql, self::cw('vote != ?'));
			$parl_totals = $this->getPieData($parl_sql, array('not presen'));
			$this->registerJS($type . '_' . $parl_var, $parl_totals);
		}
	
		protected function getPieData($sql, $params) {
			$i_count = 0;
			$raw_data = $this->DB->getArray($sql, $params);
			$output = array();
		
			foreach ($raw_data as $outcome) {
				$i_count += $outcome['count'];
				$output[$outcome['vote']] = $outcome['count'];
			}
		
			$js_totals = array();
	
			foreach($output as $name => $count) {
				$js_totals[] = array('name' => niceVoteName($name), 'y' => round($count / $i_count * 100, 1), 'color' => getPieColor($name));
			}
	
			usort($js_totals, array($this, 'sortPieChart'));
			
			return $js_totals;
		}

		public function IndividualSittingParticipation($type, $present_var, $not_present_var, $start_time_var, $last_time_var) {
			// Get member data
			$base_sql = 'SELECT DATE_FORMAT(sittings.end_time, "%%Y-%%m-%%d") as date,
					sittings.end_time, SUM(hours_present) as present,
					SUM(hours_available) - SUM(hours_present) as not_present FROM participation_data
					JOIN sittings 
						ON sittings.id = sittings_id
					JOIN members 
						ON members_id = members.id
					WHERE members_id = %s
					GROUP by date ORDER by date desc';
		
			$sql = sprintf($base_sql, self::cw('? AND end_time != 0 AND end_time >= cadency_start'));
			$data = $this->DB->getArray($sql, array($this->id));
		
			$present = array();
			$not_present = array();

			foreach ($data as $sitting) {
				$time = strtotime($sitting['end_time']) * 1000;
				$present[] =  array($time, (float) $sitting['present']);
				$not_present[] =  array($time, (float) $sitting['not_present']);
			}

			$this->registerJS($type . '_' . $present_var, $present);
			$this->registerJS($type . '_' . $not_present_var, $not_present);
			$this->registerJS($type . '_' . $last_time_var, $present[0][0]);
			$this->registerJS($type . '_' . $start_time_var, $present[5][0]);
		}

		protected function registerJS($name, $data) {
			$this->parent->registerJS('x', 'x', $name, $data);
		}
	}