<?php
	class AttendanceCharts extends Charts {
		protected function registerJS($name, $data) {
			$this->parent->registerJS('x', 'x', $name, $data);
		}

		public function ParticipationByMonth($type, $var_name, $var_month_names) {
			$data = array();
			$base_sql = 'SELECT AVG(hours_present / hours_available) as percent
				FROM participation_data
				JOIN sittings on sittings_id = sittings.id
				WHERE %s
				ORDER BY percent DESC';
				
			$where = 'DATE_FORMAT(end_time, "%m-%d") BETWEEN :start_date AND :end_date';
			$sql = sprintf($base_sql, self::cw($where));
		
			for ($i = 1; $i <= 12; $i++) {
				$data[] = $this->DB->getVar($sql, 
					array(	':start_date' => sprintf('%02d', $i) . '-01',
						':end_date' =>  sprintf('%02d', $i) . '-31'
					)
				) * 100;
			}
	
			$months = array("Sausis", 'Vasaris', 'Kovas',
						'Balandis', 'Gegužė', 'Birželis',
						'Liepa', 'Rugpjūtis', 'Rugsėjis',
						'Spalis', 'Lapkritis', 'Gruodis'
			);

			$this->registerJS($type . '_' . $var_name, $data);
			$this->registerJS($type . '_' . $var_month_names, $months);
		}
	
		public function ParticipationByFraction($type, $var_name, $var_fraction_names) {
			$base_sql = 'SELECT fraction, AVG(hours_present / hours_available) as percent
						FROM participation_data
						JOIN members 
							ON members_id = members.id
						JOIN sittings
							ON sittings.id = sittings_id
						WHERE %s
						GROUP by fraction 
						HAVING fraction 
							IN (
								SELECT DISTINCT fraction 
								FROM members 
								JOIN sitting_participation
									ON members_id = members.id
								JOIN sittings
									ON sittings.id = sittings_id
								WHERE %s
							)
						ORDER BY percent DESC';
						
			$where1 = self::cw('');
			$where2 = self::cw('cadency_end = "0000-00-00"');
			$sql = sprintf($base_sql, $where1, $where2);
				
			$array = $this->DB->getArray($sql, array());
			$fractions = array();
			$data = array();
		
			foreach ($array as $item) {
				$data[] = (float) $item['percent'] * 100;
				$fractions[] =  $item['fraction'];
			}
	
			$this->registerJS($type . '_' . $var_name, $data);
			$this->registerJS($type . '_' . $var_fraction_names, $fractions);
		}

		public function TotalParticipationBySitting($type, $var_present, $var_not_present) {
			// Get averages of each sitting
			$base_sql = '
				SELECT DATE_FORMAT(sittings.end_time, "%%Y-%%m-%%d") as date,
				SUM(hours_present) / 141 as present,
				SUM(hours_available) / 141 - SUM(hours_present) / 141 as not_present
				FROM participation_data
				JOIN sittings ON sittings.id = sittings_id
				WHERE %s
				GROUP BY date HAVING (present + not_present) > 0';
			
			$sql = sprintf($base_sql, self::cw());
			$averages = $this->DB->getArray($sql, array());

			$present = array();
			$not_present = array();
			
			foreach ($averages as $sitting) {
				$time = strtotime($sitting['date']) * 1000;
				$present[] =  array($time, (float) $sitting['present']);
				$not_present[] =  array($time, (float) $sitting['not_present']);
			}

			$this->registerJS($type . '_' . $var_present, $present);
			$this->registerJS($type . '_' . $var_not_present, $not_present);
		}
	}