<?php

class TableSQLs {
	
	protected static function getDB() {
		global $DB;
		return $DB;
	}
	
	public static function cw($where = '') {
		if ($where == '') {
			$where = '1=1';
		}
		if (CADENCY != false) {
			$where .= ' AND cadency = "' . CADENCY . '"';
		}
		return $where;
	}

	public static function getParticipationTop($side, $type) {
		$sql = '
			SELECT members.id, members.name, members_notes.notes, 
				ROUND(%1$s*100,0) as percent,
				"%2$s" as class
			FROM participation_data
			JOIN members 
				ON participation_data.members_id = members.id 
			JOIN sittings 
				ON sittings.id = participation_data.sittings_id
			LEFT JOIN members_notes 
				ON members_notes.members_id = members.id 
					AND sittings_cadency = cadency
			WHERE %3$s
			GROUP BY members.id ORDER BY percent %4$s LIMIT 3';
			
		$field = self::resolveTableType($type);
		$where = self::cw('members_notes.cadency_end IS NULL');
		list($class, $direction) = ($side == 'top') ? array('num pos', 'DESC') : array('num neg', 'ASC');
		return self::getDB()->getArray(sprintf($sql, $field, $class, $where, $direction), array());
	}
	
	public static function getParticipationAverage($type) {
		$sql = 'SELECT ROUND(%1$s*100,0)
				FROM participation_data
				JOIN sittings ON sittings_id = sittings.id
				WHERE %2$s';
		
		$field = self::resolveTableType($type);
		$where = self::cw();
		return self::getDB()->getVar(sprintf($sql, $field, $where), array());
	}
	
	protected static function resolveTableType($type) {
		switch ($type) {
			case 'official':	return 'AVG(official_presence)';
			case 'seime-lt':	return '(sum(hours_present) / sum(hours_available))';
			case 'ratio':		return '(sum(hours_present) / sum(hours_available)) / AVG(official_presence)';
		}
	}
	
	public static function getVotingTop($side, $vote) {
		$sql = 'SELECT 	members.id, members.name, 
						"" as notes,
						ROUND((count(votes.vote) / total_votes)*100,0) as percent,	
						"%1$s" as class 
				FROM votes
				JOIN 
					(
						SELECT members_id, count(vote) as total_votes
						FROM votes 
						JOIN actions 
							ON actions.id = actions_id
						JOIN questions
							ON questions.id = questions_id
						JOIN sittings 
							ON sittings_id = sittings.id
						WHERE %4$s GROUP BY members_id
					) as a
					ON votes.members_id = a.members_id
				JOIN members
					ON members.id = votes.members_id 
				JOIN actions 
					ON actions.id = actions_id
				JOIN questions
					ON questions.id = questions_id
				JOIN sittings 
					ON sittings_id = sittings.id
				LEFT JOIN members_notes 
				ON members_notes.members_id = members.id 
					AND sittings_cadency = cadency
				WHERE %2$s
				GROUP BY members.id
				ORDER BY percent %3$s LIMIT 3';				
		$where = self::cw('votes.vote = ? AND members_notes.cadency_end IS NULL');
		$where2 = self::cw('vote != "not presen"');
		list($class, $direction) = ($side == 'top') ? array('num', 'DESC') : array('num', 'ASC');
		return self::getDB()->getArray(sprintf($sql, $class, $where, $direction, $where2), array($vote));
	}
	
	public static function getVotingAverage($vote) {
		$base_sql = 'SELECT count(vote) FROM votes 
						JOIN actions 
							ON actions.id = actions_id
						JOIN questions
							ON questions.id = questions_id
						JOIN sittings 
							ON sittings_id = sittings.id
						WHERE %s';
						
		$where1 = self::cw('vote = ?');
		$where2 = self::cw('vote <> "not presen"');
		$sql = "SELECT ROUND(($base_sql) / ($base_sql)*100,0)";		
		return self::getDB()->getVar(sprintf($sql, $where1, $where2), array($vote));
	}
	
	public static function getNoVotingTop($side, $vote) {
		$sql = 'SELECT 	members.id, members.name, 
						"" as notes,
						count(votes.vote) as percent,	
						"%1$s" as class 
				FROM votes
				JOIN members
					ON members.id = votes.members_id 
				JOIN actions 
					ON actions.id = actions_id
				JOIN questions
					ON questions.id = questions_id
				JOIN sittings 
					ON sittings_id = sittings.id
				LEFT JOIN members_notes 
				ON members_notes.members_id = members.id 
					AND sittings_cadency = cadency
				WHERE %2$s
				GROUP BY members.id
				ORDER BY percent %3$s LIMIT 3
		';
		$where = self::cw('votes.vote = ? AND members_notes.cadency_end IS NULL');
		list($class, $direction) = ($side == 'top') ? array('num', 'DESC') : array('num', 'ASC');
		return self::getDB()->getArray(sprintf($sql, $class, $where, $direction), array($vote));				
	}
	
	public static function getNoVotingAverage($vote) {
		$sql = 'SELECT 
					ROUND(count(votes.vote) / 141,0)
				FROM votes
				JOIN members
					ON members.id = votes.members_id 
				JOIN actions 
					ON actions.id = actions_id
				JOIN questions
					ON questions.id = questions_id
				JOIN sittings 
					ON sittings_id = sittings.id
				WHERE %1$s
		';
		$where = self::cw('votes.vote = ?');
		return self::getDB()->getVar(sprintf($sql, $where), array($vote));
	}
}
