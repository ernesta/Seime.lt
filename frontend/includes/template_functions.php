<?php

	/** Top tables **/
	function getParticipationTop() {
		$top = TableSQLs::getParticipationTop('top', 'seime-lt');
		$bottom = array_reverse(TableSQLs::getParticipationTop('bottom', 'seime-lt'));
		$average = TableSQLs::getParticipationAverage('seime-lt');	
		return getTopTable($top, $bottom, $average, 'num avg', '%');	
	}

	function getOfficialParticipationTop() {
		$top = TableSQLs::getParticipationTop('top', 'official');
		$bottom = array_reverse(TableSQLs::getParticipationTop('bottom', 'official'));
		$average = TableSQLs::getParticipationAverage('official');
		return getTopTable($top, $bottom, $average, 'num avg', '%');	
	}

	function getParticipationRatio() {
		$top = TableSQLs::getParticipationTop('top', 'ratio');
		$bottom = array_reverse(TableSQLs::getParticipationTop('bottom', 'ratio'));
		$average = TableSQLs::getParticipationAverage('ratio');
		return getTopTable($top, $bottom, $average, 'num avg', '%');	
	}

	function getVotingTop($vote) {
		$data = getCached('getVotingTop', $vote);
		
		if (false === $data) {
			$top = TableSQLs::getVotingTop('top', $vote);
			$bottom = array_reverse(TableSQLs::getVotingTop('bottom', $vote));
			$average = TableSQLs::getVotingAverage($vote);
			saveCached('getVotingTop', $vote, array($top, $average, $bottom));
		} else {
			list($top, $average, $bottom) = $data;
		}
		
		return getTopTable($top, $bottom, $average, 'num pos', '%');	
	}

	function getNovotingTop($vote) {
		$top = TableSQLs::getNoVotingTop('top', $vote);
		$bottom = array_reverse(TableSQLs::getNoVotingTop('bottom', $vote));
		$average = TableSQLs::getNoVotingAverage($vote);
		return getTopTable($top, $bottom, $average, 'num pos', '');	
	}

	function getTopTable($top, $bottom, $average, $average_class = 'num avg', $char = '%') {
		$table = array();
		
		foreach ($top as $member) {
			empty($member['notes']) ? $name = $member['name'] : $name = '<acronym title="' . htmlspecialchars($member['notes']) . '">' . $member['name'] . '</acronym>';
			
			$table[] = '<td class="' . $member['class'] . '">' . $member['percent'] . $char . '</td>
			<td><a href="' . getMemberLink($member['id']) . '">' . $name . '</a></td>';
		}

		$table[] = '<td class="' . $average_class . '">' . $average . $char . '</td><td>Seimo vidurkis</td>';

		foreach ($bottom as $member) {
			empty($member['notes']) ? $name = $member['name'] : $name = '<acronym title="' . htmlspecialchars($member['notes']) . '">' . $member['name'] . '</acronym>';
			
			$table[] ='<td class="' . $member['class'] . '">' . $member['percent'] . $char . '</td>
			<td><a href="' . getMemberLink($member['id']) . '">' . $name . '</a></td>';
		}

		return '<table><tr>' . implode('</tr><tr>', $table) . '</tr></table>';
	}

	/** Top tables end **/
	/** Individual data */
	function populateIndividualData($id = '') {
		global $DB;
		global $MEMBER;
	
		if (empty($id)) $id = $_GET['id'];
		
		$sql = sprintf(
			'SELECT
				members.name, members.id, members.image_src, members.fraction,
				members_notes.cadency_start, members_notes.cadency_end, members_notes.notes
			FROM members
			JOIN sitting_participation
			ON members_id = members.id
			JOIN sittings
			ON sittings_id = sittings.id
			LEFT JOIN members_notes
			ON sittings_cadency = sittings.cadency AND members_notes.members_id = members.id
			WHERE %s', TableSQLs::cw('members.id = ?'));
			
		$MEMBER = $DB->getArray($sql, array($id));
		
		if (empty($MEMBER)) {
			return false;
		} else {
			define('MEMBER', $MEMBER[0]['name']);
			define('MEMBER_ID', $MEMBER[0]['id']);
			define('MEMBER_IMAGE', $MEMBER[0]['image_src']);
			define('MEMBER_FRACTION', $MEMBER[0]['fraction']);
			define('MEMBER_START', $MEMBER[0]['cadency_start']);
			define('MEMBER_END', $MEMBER[0]['cadency_end']);
			define('MEMBER_NOTES', $MEMBER[0]['notes']);
			
			return true;
		}
	}

	function getIndividualVotingAbsolute($vote) {
		return IndividualSQLs::getIndividualVotingAbsolute($vote, MEMBER_ID);
	}

	function getIndividualParticipation() {
		return IndividualSQLs::getIndividualParticipation(MEMBER_ID) . '%';
	}

	function getOfficialParticipation() {
		return IndividualSQLs::getOfficialParticipation(MEMBER_ID) . '%';
	}

	/** Individual data end */
	function getMemberList() {	
		// Get all the fractions
		$string = '';
		$i = 0;
		$first_list = array();
		$second_list = array();
	
		$members = IndividualSQLs::getMemberList();
	
		foreach ($members as $member) {
			if ($i++ % 2 == 0) $first_list[] = $member;
			else $second_list[] = $member;
		}

		$string = '<ul id=firstList>';
	
		foreach ($first_list as $member) {
			if ($member['cadency_end'] != '0000-00-00') {
				$fraction = '<em>Seime iki ' . $member['cadency_end'] . '</em>';
				$f_class = 'ended';
			} else {
				$fraction = getFractionName($member['fraction']);
				$f_class = $member['fraction'];
			}
		
			$string .= '<li class="listMember clearfix ' . $f_class . '"><div>';
			$string .= '<div class=listImg><img src="' . getThumb($member['id'])  . '" alt="' . $member['name'] . '" /></div>';
			$string .= '<a href="' . getMemberLink($member['id'])  . '" class=listName>' . $member['name'] . '</a>';
			$string .= '<div class=listFraction>' . $fraction  . '</div>';
			$string .= '</div></li>';
		}
		
		$string .= '</ul>';
		
		$string .= '<ul>';
	
		foreach ($second_list as $member) {
			if ($member['cadency_end'] != '0000-00-00') {
				$fraction = '<em>Seime iki ' . $member['cadency_end'] . '</em>';
				$f_class = 'ended';
			} else {
				$fraction = getFractionName($member['fraction']);
				$f_class = $member['fraction'];
			}
		
			$string .= '<li class="listMember clearfix ' . $f_class . '"><div>';
			$string .= '<div class=listImg><img src="' . getThumb($member['id'])  . '" alt="' . $member['name'] . '" /></div>';
			$string .= '<a href="' . getMemberLink($member['id']) . '" class=listName>' . $member['name'] . '</a>';
			$string .= '<div class=listFraction>' . $fraction . '</div>';
			$string .= '</div></li>';
		}
	
		$string .= '</ul>';

		return $string;;
	}

	function getNTAKKList() {	
		// Get all the fractions
		$string = '';
		$i = 0;
	
		$members = IndividualSQLs::getNTAKKList();
	
		foreach ($members as $member) {
			if ($member['cadency_end'] != '0000-00-00') {
				$fraction = '<em>Seime iki ' . $member['cadency_end'] . '</em>';
				$f_class = 'ended';
			} else {
				$fraction = getFractionName($member['fraction']);
				$f_class = $member['fraction'];
			}
						
			$string .= '<li ';
			$string .= 'data-one="' . $member['name'] . '" ';
			$string .= 'data-two="' . $member['alcohol_rating'] . '" ';
			$string .= 'data-three="' . $member['tobacco_rating'] . '" ';
			$string .= 'data-four="' . $member['full_rating'] . '" ';
			$string .= 'class="listMember clearfix ' . $f_class . '">';
			$string .= '<div class=member>';
			$string .= '<div class=listImg><img src="' . getThumb($member['id'])  . '" alt="' . $member['name'] . '" /></div>';
			$string .= '<a href="' . getMemberLink($member['id'])  . '" class=listName>' . $member['name'] . '</a>';
			$string .= '<div class=listFraction>' . $fraction  . '</div>';
			$string .= '</div>';
			$string .= '<div class=ratings><span>' . $member['alcohol_rating'] . '</span><span>' . $member['tobacco_rating'] . '</span><span>' . $member['full_rating'] . '</span></div>';
			$string .= '</li>';
		}

		return $string;;
	}

	function getRawFractionList() {
		$list = IndividualSQLs::getFractions();
		$fractions = array();
		
		foreach($list as $l) {
			$fractions[$l['fraction']] = getFractionName($l['fraction']);
		}
		
		asort($fractions);
		
		return $fractions;
	}

	function getThumb($id) {
//		return 'http://seime.lt/images/people/thumbs/' . $id . '.jpg';
		return 'frontend/content/people/thumbs/' . $id . '.jpg';
	}

	function getImage($id) {
//		return 'http://seime.lt/images/people/full/' . $id . '.jpg';
		return 'frontend/content/people/full/' . $id . '.jpg';
	}

	function getFractionList() {
		$list = IndividualSQLs::getFractions();
		$output = array();
		
		foreach ($list as $row) {
			$output[] = $row['fraction'] . ' - ' . getFractionName($row['fraction']);
		}
	
		return implode(', ', $output);
	}

	function getPageTitle() {
		$title = 'Seime.lt';
	
		switch (PAGE_VIEW) {
			case 'attendance': $title .= ' | Lankomumo statistika'; break;
			case 'error': $title .= ' | Puslapis nerastas'; break;
			case 'individual': $title .= ' | ' . MEMBER; break;
			case 'list': $title .= ' | Seimo narių sąrašas'; break;
			case 'main': break;
			case 'sitting': $title .= ' | ' . getSittingHeader(SITTING_ID); break;
			case 'sitting-list': $title .= ' | Seimo posėdžių sąrašas'; break;
			case 'voting': $title .= ' | Balsavimo statistika'; break;
		}
	
		return $title;
	}

	function getPageType() {
		if (PAGE_VIEW == 'individual') return 'politician';
		else return 'website';
	}

	function getPageImage() {
		if (PAGE_VIEW == 'individual') return getImage(MEMBER_ID);
		else return 'http://seime.lt/images/seime.png';
	}

	function getCurrentURL() {
		return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}

	function getShareURL() {
		if (PAGE_VIEW !== 'error') return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		else return 'http://seime.lt/';
	}

	function getShareTitle() {
		if (PAGE_VIEW == 'error') return 'Seime.lt';
		else return getPageTitle();
	}

	function getSummary() {
		if (PAGE_VIEW == 'individual') return 'Ką ' . MEMBER . ' veikia visą dieną';
		elseif (PAGE_VIEW == 'sitting') return 'Ką Seimas veikė ' . SITTING_DATE . ' dieną';
		else return 'Ką Seimas veikia visą dieną';
	}

	function getMemberGrid() {
		$members = IndividualSQLs::getMemberList();
		shuffle($members);

		$row = 5;
		$count = 17;

		for ($i = 0; $i < $row; $i++) {
			echo '<ul>';
	
			for ($j = 0; $j < $count; $j++) {
				$n = $count * $i + $j;
				echo '<li><a href="'
					. getMemberLink($members[$n]['id'])
					. '"><img src="'
					. getThumb($members[$n]['id'])
					. '" alt="'
					. $members[$n]['name']
					. '" title="'
					. $members[$n]['name']
					. '" /></a></li>';
			}
		
			echo '</ul>';
		}
	}

	function getSittingLink($id) {
		return 'http://seime.lt/posedziai/' . $id;
	}

	function getSittingHeader($id) {
		global $DB;
		setlocale(LC_TIME, 'lt_LT.UTF8');
		mb_internal_encoding('UTF-8');
	
		$type = $DB->getVar('SELECT type FROM sittings WHERE id = ?', array($id));
		$end_time = $DB->getVar('SELECT end_time FROM sittings WHERE id = ?', array($id));
		$date = strftime('%Y m. %B %e d.', strtotime($end_time));
		
		return "$date $type posėdis";
	}

	function getSittingImage($id) {	
		if (file_exists("frontend/content/sitting-dynamics/sitting-dynamics-$id.png")) {
			return '<img src="frontend/content/sitting-dynamics/sitting-dynamics-' . $id . '.png" />';
		}
		
		else return false;
	}

	function getCadencyNavigation() {
		$cadencies = getCadencies();
		
		// Setup available cadencies
		if (in_array(PAGE_VIEW, array('list', 'attendance', 'voting'))) {
			$avail_cadencies = $cadencies;
		} elseif (PAGE_VIEW == 'individual' ) {
			$avail_cadencies = getCadencies(MEMBER_ID);
		} else {
			return;
		}
	
		echo '<ul id="cadencyNav">';
		
		foreach ($cadencies as $cadency) {
			if ($cadency === CADENCY) {
				$class = 'active';
			} elseif (in_array($cadency, $avail_cadencies)) {
				$class = 'inactive';
			} else {
				$class = 'disabled';
			}
		
			echo '<li><a class="' . $class	. '"';
		
			if ($class != 'disabled') {
				echo ' href="' . getLinkToPage(null, $cadency) . '"';
			}
			
			echo '>' . $cadency . '</a></li>';
		}
		
		echo '</ul>';
	}

	function getCadencies($member_id = null) {
		global $DB;
	
		if (!$member_id) {
			$s = $DB->prepare('SELECT DISTINCT cadency FROM sittings ORDER BY end_time ASC');
			$s->execute();
		} else {
			$s = $DB->prepare('
				SELECT DISTINCT cadency FROM sittings
				JOIN sitting_participation ON sittings.id = sittings_id
				WHERE members_id = ? 
				ORDER BY end_time ASC
			');
		
			$s->execute(array($member_id));
		}
	
		return $s->fetchAll(PDO::FETCH_COLUMN);
	}

	function getLinkToPage($page, $cadency = null) {
		if ($page == null) {
			switch(PAGE_VIEW) {
				case 'attendance': $page = 'lankomumas'; break;
				case 'individual': $page = 'nariai/' . MEMBER_ID; break;
				case 'list': $page = 'nariai'; break;
				case 'sitting': $page = 'posedziai/' . SITTING_ID; break;
				case 'sitting-list': $page = 'posedziai';
				case 'voting': $page = 'balsavimas'; break;
				default: $page = ''; break;
			}
		}
		
		if ($cadency == null) {
			$cadency == CADENCY;
		}
		
		if ($cadency != CURRENT_CADENCY) {
			return $page . '/' . $cadency;
		} else {
			return $page;
		}
	}
