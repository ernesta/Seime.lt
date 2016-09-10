<?php
	abstract class Charts {
		protected $DB;
		protected $parent;

		public function __construct(DB $db, SeimeLT $parent) {
			$this->DB = $db;
			$this->parent = $parent;
		}
	
		protected static function cw($where = '') {
			return TableSQLs::cw($where);
		}

		protected function sortPieChart($a, $b) {
			if ($this->getPieOrder($a['name']) > $this->getPieOrder($b['name'])) return 1;
			elseif ($this->getPieOrder($a['name']) < $this->getPieOrder($b['name'])) return -1;
			else return 0;
		}

		protected function getPieOrder($name) {
			$v = 1;
	
			switch ($name) {
				case 'Balsavo UŽ': $v = 2; break;
				case 'Balsavo PRIEŠ': $v = 3; break;
				case 'Susilaikė': $v = 4; break;
				case 'Neužsiregistravo': $v = 1; break;
				case 'Užsiregistravo, tačiau nebalsavo': $v = 5; break;
			}
	
			return $v;
		}
	}