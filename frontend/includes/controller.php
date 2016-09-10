<?php
	class MyLogger {
		static $prints = array();
		public static function log($m) {
			self::$prints[] = print_r($m, 1);
			self::$prints[] = print_r($_GET, 1);
		}
	}
	
	require_once('includes.php');
	Initialisator::initialise();
	
	require_once('handler_functions.php');	
	require_once('template_functions.php');
	require_once('classes/SeimeLT.php');
	require_once('classes/SittingsList.php');
	require_once('classes/sql/IndividualSQLs.php');
	require_once('classes/sql/TableSQLs.php');
	require_once('classes/charts/Charts.php');
	require_once('classes/charts/AttendanceCharts.php');
	require_once('classes/charts/IndividualCharts.php');
	require_once('classes/charts/IntroCharts.php');
	require_once('classes/charts/SittingCharts.php');
	require_once('classes/charts/VotingCharts.php');
	
	$OBJECTS = array();
	define('CURRENT_CADENCY', '2012-2016');
	global $DB;
	
	try { 
		$DB = Initialisator::getDB();		
		// Make sure cadency is all right 
		if (isset($_GET['cadency'])) {
			$r = $DB->getVar('SELECT cadency FROM sittings WHERE cadency = ?', array($_GET['cadency']));
			
			if ($r != false) {
				define('CADENCY', $_GET['cadency']);
			} else {
				define('PAGE_VIEW', 'error');
			}
		} else {
			define('CADENCY', CURRENT_CADENCY);
		}
		
		// Define page view 
		if (defined('PAGE_VIEW')) {
			// Do nothing
		}
		
		// main.php
		elseif (!isset($_GET['page'])) {
			define('PAGE_VIEW', 'main');
			$SeimeLT = new SeimeLT($DB, 'Intro');
			$SeimeLT->RegisterChart('SittingParticipationExtract', array('PresentData', 'NotPresentData', 'Name'));
			$SeimeLT->RegisterChart('RandomSittingOverTime', array('Count', 'Labels'));
			$OBJECTS[] = $SeimeLT;		
				
			$SeimeLT = new SeimeLT($DB, 'Voting');
			$SeimeLT->RegisterChart('TotalVotePie', array('Data'));
			$OBJECTS[] = $SeimeLT;			
			
			$SeimeLT = new SeimeLT($DB, 'Attendance');
			$SeimeLT->RegisterChart('ParticipationByFraction', array('Data', 'Fractions'));
			$OBJECTS[] = $SeimeLT;
		}
		
		// individual.php
		elseif (isset($_GET['id'])) {
			if (false !== populateIndividualData($_GET['id'])) {
				define('PAGE_VIEW', 'individual');
				$SeimeLT = new SeimeLT($DB, 'Individual', $_GET['id']);
				$OBJECTS[] = $SeimeLT;
				$SeimeLT->RegisterChart('IndividualPieChart', array('IndividualData', 'TotalData'));
				$SeimeLT->RegisterChart('IndividualSittingParticipation', array('PresentData', 'NotPresentData', 'StartTime', 'EndTime'));
			} else define('PAGE_VIEW', 'error');
		}
		
		// voting.php
		elseif ($_GET['page'] == 'voting') {
			define('PAGE_VIEW', 'voting');
			$SeimeLT = new SeimeLT($DB, 'Voting');
			$SeimeLT->RegisterChart('TotalVotePie', array('Data'));
			$SeimeLT->RegisterChart('VotesByOutcome', array('AcceptedData', 'RejectedData'));
			$SeimeLT->RegisterChart('AcceptRatesInVotings', array('Data'));
			$SeimeLT->RegisterChart('ParticipationBySitting', array('Data'));
			$OBJECTS[] = $SeimeLT;			
		}
		
		// attendance.php
		elseif ($_GET['page'] == 'attendance') {
			define('PAGE_VIEW', 'attendance');
			$SeimeLT = new SeimeLT($DB, 'Attendance');
			$SeimeLT->RegisterChart('ParticipationByMonth', array('Data', 'Months'));
			$SeimeLT->RegisterChart('ParticipationByFraction', array('Data', 'Fractions'));
			$SeimeLT->RegisterChart('TotalParticipationBySitting', array('PresentData', 'NotPresentData'));			
			$OBJECTS[] = $SeimeLT;
		}
		
		// individual.php
		elseif ($_GET['page'] == 'individual') {
			define('PAGE_VIEW', 'list');
		}
		
		// ntakk.php
		elseif ($_GET['page'] == 'NTAKK') {
			define('PAGE_VIEW', 'NTAKK');
		}
		
		// sitings.php
		elseif ($_GET['page'] == 'sittings') {
			define('PAGE_VIEW', 'sittings');
		}
		
		// sitting.php
		elseif ($_GET['page'] == 'sitting') {
			if (!isset($_GET['sitting_id'])) {
				define('PAGE_VIEW', 'sittings-list');
				$OBJECTS[] = new SittingsList($DB);
			} else {
				try {
					$SeimeLT = new SeimeLT($DB, 'Sitting', $_GET['sitting_id']);
					$SeimeLT->RegisterChart('SittingDynamics', array('Series', 'Members', 'Labels'));
					$SeimeLT->RegisterChart('SittingCountOverTime', array('Counts', 'CountLabels'));
					$OBJECTS[] = $SeimeLT;
					define('PAGE_VIEW', 'sitting');
					define('SITTING_ID', $_GET['sitting_id']);
				} catch (Exception $e) {
					define('PAGE_VIEW', 'error');
				}
			}
		}
		
		// labs.php
		elseif ($_GET['page'] == 'labs') {
			define('PAGE_VIEW', 'labs');
			$SeimeLT = new SeimeLT($DB, 'Sitting');
			$SeimeLT->RegisterChart('SittingDynamics', array(500183, 'Series', 'Sitting', 'Members', 'Labels', 'Counts', 'CountLabels'));
			$OBJECTS[] = $SeimeLT;
		}
		
		// error.php
		else define('PAGE_VIEW', 'error');		
	}
	
	catch(Exception $e) {
		$_GET['page'] = 'error';
		define('PAGE_VIEW', 'error');
	}
	
	if (PAGE_VIEW === 'error') header("HTTP/1.0 404 Not Found");