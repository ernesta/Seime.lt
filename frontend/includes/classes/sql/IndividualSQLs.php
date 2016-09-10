<?php

class IndividualSQLs {
	
	protected static function getDB() {
		global $DB;
		return $DB;
	}
	
	protected static function cw($where = '') {
		return TableSQLs::cw($where);
	}
	
	public static function getIndividualVotingAbsolute($vote, $member_id) {
		$sql = 'SELECT count(votes.vote) 
				FROM votes 
				JOIN actions 
					ON actions.id = actions_id
				JOIN questions
					ON questions.id = questions_id
				JOIN sittings 
					ON sittings_id = sittings.id
				WHERE %s';
		$where = self::cw('members_id = :member_id AND votes.vote = :vote');
		return self::getDB()->getVar(sprintf($sql, $where), array(':member_id' => $member_id, ':vote' => $vote));
	}
	
	public static function getIndividualParticipation($member_id) {
		$sql = 'SELECT 
					ROUND((sum(hours_present) / sum(hours_available))*100,0) 
				FROM participation_data 
				JOIN sittings 
					ON sittings_id = sittings.id
				WHERE %s';
		$where = self::cw('members_id = ?');
		return self::getDB()->getVar(sprintf($sql, $where), array($member_id));
	}
	
	public static function getOfficialParticipation($member_id) {
		$base_sql = 'SELECT count(sitting_participation.id) 
						FROM sitting_participation
						JOIN sittings
							ON sittings.id = sittings_id
						WHERE %s';	
		$where1 = self::cw('members_id = ? AND presence = 1');
		$where2 = self::cw('members_id = ?');
		$sql = "SELECT ROUND(($base_sql) / ($base_sql)*100,0)";
		return self::getDB()->getVar(sprintf($sql, $where1, $where2), array($member_id, $member_id));		
	}
	
	public static function getMemberList() {
		$sql = 'SELECT DISTINCT name, members.id, image_src, fraction, cadency_end
				FROM members
				JOIN sitting_participation
					ON members_id = members.id
				JOIN sittings 
					ON sittings_id = sittings.id
				WHERE %s
				ORDER BY name ASC';
		$where = self::cw();
		return self::getDB()->getArray(sprintf($sql, $where), array());
	}
	
	public static function getFractions() {
		$sql = 'SELECT DISTINCT fraction 
				FROM members 
				JOIN sitting_participation
					ON members_id = members.id
				JOIN sittings 
					ON sittings_id = sittings.id
				WHERE %s
				ORDER BY fraction ASC';
		$where = self::cw('cadency_end = ?');
		return self::getDB()->getArray(sprintf($sql, $where), array('0000-00-00'));
	}
		
	
	/*
	public static function getIndividualVoting($vote) {
		$sql = '
			SELECT (count(votes.vote) / total_votes) as percent
			FROM votes
			JOIN 
				(
					SELECT members_id, count(vote) as total_votes FROM votes
					JOIN actions 
						ON actions.id = actions_id
					JOIN questions
						ON questions.id = questions_id
					JOIN sittings 
						ON sittings_id = sittings.id
					WHERE %1$s
				) as a
				ON votes.members_id = a.members_id
			JOIN actions 
				ON actions.id = actions_id
			JOIN questions
				ON questions.id = questions_id
			JOIN sittings 
				ON sittings_id = sittings.id
			WHERE %2$s';
		
		$where1 = self::cw('members_id = :member_id');
		$where2 = self::cw('votes.vote = :vote');
		printf($sql, $where1, $where2);
		return self::getDB()->getVar(sprintf($sql, $where1, $where2), array(':member_id' => MEMBER_ID, ':vote' => $vote));
	} */
}
