<?php
	class VotingCharts extends Charts {
		protected function registerJS($name, $data) {
			$this->parent->registerJS('x', 'x', $name, $data);
		}

		public function ParticipationBySitting($type, $var_name) {
			$output = getCached($type, '');
	
			if (false === $output) {
				$base_sql = 'SELECT count(members_id) as count 
					FROM votes 
					JOIN actions 	
					ON actions.id = actions_id
					JOIN questions
					ON questions.id = questions_id
					JOIN sittings 
					ON sittings_id = sittings.id
					WHERE %s 
					GROUP BY actions_id';
			
				$sql = sprintf($base_sql, self::cw('vote != ?'));
				$data = $this->DB->getArray($sql, array('not presen'));
			
				// Populate data array
				$output = array();
				$j = 0;
			
				for ($i = 0; $i < 140; $i += 10) {
					$output[$j++] = 0;
				}
			
				foreach ($data as $sitting) {
					if ($sitting['count'] > 140) $bucket = 13;
					else $bucket = floor(($sitting['count'] - 1) / 10);
				
					$output[$bucket]++;
				}
			
				saveCached($type, '', $output);
			}
			
			$this->registerJS($type . '_' . $var_name, $output);
		}

		public function TotalVotePie($type, $var_name) {
			$base_sql = 'SELECT vote, count(vote) as count 
						FROM votes
						JOIN actions 
							ON actions.id = actions_id
						JOIN questions
							ON questions.id = questions_id
						JOIN sittings 
							ON sittings_id = sittings.id
						WHERE %s 
						GROUP BY vote';
		
			$sql = sprintf($base_sql, self::cw('vote != ?'));
			$total_data = $this->DB->getArray($sql, array('not presen'));
			$totals = array();
			$total_count = 0;
	
			foreach ($total_data as $outcome) {
				$total_count += $outcome['count'];
				$totals[$outcome['vote']] = $outcome['count'];
			}
	
			$js_totals = array();
		
			foreach($totals as $name => $count) {
				$data = array('name' => niceVoteName($name), 'y' => $count / $total_count * 100);
			
				if ($name == 'disappeare') {
					$data['sliced'] = 1;
					$data['selected'] = 1;
				}
		
				$js_totals[] = $data;
			}
	
			usort($js_totals, array($this, 'sortPieChart'));
			$this->registerJS($type . '_' . $var_name, $js_totals);
		}

		public function VotesByOutcome($type, $var_accepted, $var_rejected) {
			$data = getCached($type, '');
		
			if (false === $data) {
				$base_sql = 'SELECT outcome, vote, COUNT( vote ) AS count
							FROM votes
							JOIN actions 
								ON actions.id = actions_id
							JOIN questions
								ON questions.id = questions_id
							JOIN sittings 
								ON sittings_id = sittings.id
							WHERE %s 
							GROUP BY outcome, vote';

				$sql = sprintf($base_sql, self::cw());
				$array = $this->DB->getArray($sql, array());
			
				$accepted = array();
				$rejected = array();
	
				foreach ($array as $row) {
					if ($row['outcome'] == 'accepted') {
						$accepted[$row['vote']] = $row['count'];
					} else {
						$rejected[$row['vote']] = $row['count'];
					}
				}
				
				uksort($accepted, array($this, 'sortVotes'));
				uksort($rejected, array($this, 'sortVotes'));
				$accepted = array_values($accepted);
				$total_accepted = array_sum($accepted);

				foreach ($accepted as &$point) {
					$point = $point / $total_accepted * 100;
				}

				$rejected = array_values($rejected);
				$total_rejected = array_sum($rejected);

				foreach ($rejected as &$point) {
					$point = $point / $total_rejected * 100;
				}

				$data = array('accepted' => $accepted, 'rejected' => $rejected);
				saveCached($type, '', $data);
			}
		
			$this->registerJS($type . '_' . $var_accepted, $data['accepted']);
			$this->registerJS($type . '_' . $var_rejected, $data['rejected']);
		}

		public function AcceptRatesInVotings($type, $var_name) {
			$data = getCached($type, '');

			if (false === $data) {
				$base_sql = 'SELECT actions.id 
							FROM actions 
							JOIN questions
								ON questions.id = questions_id
							JOIN sittings 
								ON sittings_id = sittings.id
							WHERE %s';
			
				$sql = sprintf($base_sql, self::cw('actions.type = ? AND actions.outcome = ?'));
			
				// Find all votings which ended negatively
				$ids = $this->DB->getArray($sql, array('voting', 'rejected'));

				// Populate buckets
				$output = array();
				$j = 0;
			
				for ($i = 0; $i < 100; $i += 10) {
					$output[$j++] = 0;
				}

				// Get data and put into buckets
				foreach ($ids as $row) {
					$total = $this->DB->getVar('SELECT count(vote) FROM votes WHERE actions_id = ? AND vote != ?', array($row['id'], 'not presen'));
					$positive = $this->DB->getVar('SELECT count(vote) FROM votes WHERE actions_id = ? AND vote = ?', array($row['id'], 'accept'));
					$percent = $positive / $total * 100;
					$bucket = floor($percent / 10);
					if ($bucket == 10) $bucket = 9;
					$output[$bucket]++;
				}
			
				saveCached($type, '', $output);
				$data = $output;
			}
			$this->registerJS($type . '_' . $var_name, $data);
		}

		protected function sortVotes($a, $b) {
			if ($this->getVoteOrder($a) > $this->getVoteOrder($b)) return 1;
			elseif ($this->getVoteOrder($a) < $this->getVoteOrder($b)) return -1;
			else return 0;
		}

		protected function getVoteOrder($name) {
			$v = 1;
			switch ($name) {
				case 'accept': $v = 1; break;
				case 'reject': $v = 2; break;
				case 'abstain': $v = 3; break;
				case 'disappeare': $v = 4; break;
				case 'not presen': $v = 5; break;
			}
	
			return $v;
		}
	}