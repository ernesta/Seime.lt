<?php
	function includePageTemplate() {
		switch (PAGE_VIEW) {
			case 'attendance': include('frontend/pages/attendance.php'); break;
			case 'error': include('frontend/pages/error.php'); break;
			case 'individual': include('frontend/pages/individual.php'); break;
			case 'list': include('frontend/pages/list.php'); break;
			case 'main': include('frontend/pages//main.php'); break;
			case 'NTAKK': include('frontend/pages/ntakk.php'); break;
			case 'sitting': include('frontend/pages//sitting.php'); break;
			case 'sittings-list': include('frontend/pages/sittings-list.php'); break;
			case 'voting': include('frontend/pages/voting.php'); break;
		}
	}

	function getSiteNavigation() {
		?>
			<ul class="tabnav">
				<li id="tabAtt">
					<a href="<?php echo Initialisator::getBaseURL() . cadencify('lankomumas'); ?>">
						<img style="<?php if(PAGE_VIEW == 'attendance') echo 'z-index:10; margin-left:0px' ?>" src="frontend/resources/images/navigation/<?php if(PAGE_VIEW == 'attendance') echo 'tab-lank-dark.png'; else echo 'tab-lank.png'; ?>" alt="Lankomumo statistika" />
					</a>
				</li>

				<li id="tabVote">
					<a href="<?php echo Initialisator::getBaseURL() . cadencify('balsavimas'); ?>">
						<img style="<?php if(PAGE_VIEW == 'voting') echo 'z-index:10; margin-left:0px' ?>" src="frontend/resources/images/navigation/<?php if(PAGE_VIEW == 'voting') echo 'tab-bals-dark.png'; else echo 'tab-bals.png'; ?>" alt="Balsavimo statistika" />
					</a>
				</li>

				<li id="tabInd">
					<a href="<?php echo Initialisator::getBaseURL() . cadencify('nariai'); ?>">
						<img style="<?php if(PAGE_VIEW == 'list' || PAGE_VIEW == 'individual') echo 'z-index:10; margin-left:0px' ?>" src="frontend/resources/images/navigation/<?php if(PAGE_VIEW == 'list' || PAGE_VIEW == 'individual') echo 'tab-ind-dark.png'; else echo 'tab-ind.png'; ?>" alt="Individuali statistika" />
					</a>
				</li>

				<li>
					<a href="<?php echo Initialisator::getBaseURL(); ?>posedziai">
						<img style="<?php if(PAGE_VIEW == 'sitting' || PAGE_VIEW == 'sittings-list') echo 'z-index:10; margin-left:0px' ?>" src="frontend/resources/images/navigation/<?php if(PAGE_VIEW == 'sitting' || PAGE_VIEW == 'sittings-list') echo 'tab-pos-dark.png'; else echo 'tab-pos.png'; ?>" alt="Posėdžių statistika" />
					</a>
				</li>
				
				<li>
					<a href="<?php echo Initialisator::getBaseURL(); ?>NTAKK">
						<img style="<?php if(PAGE_VIEW == 'NTAKK') echo 'z-index:10; margin-left:0px' ?>" src="frontend/resources/images/navigation/<?php if(PAGE_VIEW == 'NTAKK') echo 'tab-ntakk-dark.png'; else echo 'tab-ntakk.png'; ?>" alt="NTAKK statistika" />
					</a>
				</li>
			</ul>
		<?php
	}

	if (!function_exists('print_f')) {
		function print_f($array) {
			echo '<pre>';
			print_r($array);
			echo '</pre>';
		}
	}

	function getCached($function, $params) {
		global $DB;
		$path = 'cache';
		
		if (CADENCY != false) {
			$path = $path . '/' . CADENCY;
		}
		
		$key = "$path/$function-$params.cache";
		$data = $DB->getVar("SELECT `data` FROM datastore WHERE `key` = ?", array($key));
		
		if ($data == false) {
			return false;
		} else {
			return json_decode($data, true);
		}
	}

	function saveCached($function, $params, $data) {
		global $DB;
		$path = 'cache';
		
		if (CADENCY != false) {
			$path = $path . '/' . CADENCY;
		}
		
		$key = "$path/$function-$params.cache";
		$DB->getVar('INSERT INTO datastore (`key`, `data`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `data` = VALUES(`data`)', array($key, json_encode($data)));
	}

	function getFractionName($fraction) {
		switch($fraction) {
			case 'CF': return 'Centro frakcija';
			case 'DKF':
				if (CADENCY == '2012-2016') {
					return 'Frakcija "Drąsos kelias"';
				} elseif (CADENCY == '1996–2000') {
					return 'Demokratų krikščionių frakcija';
			}
			case 'DPF': return 'Darbo partijos frakcija';
			case 'JF': 
				if (CADENCY == '2012-2016') {
					return 'Jungtinė (Liberalų ir centro sąjungos ir Tautos prisikėlimo partijos) frakcija';
				} elseif (CADENCY == '1996-2000') {
					return 'Jungtinė frakcija';
			}
			case 'KDF': return 'Krikščionių demokratų frakcija';
			case 'KPF': return 'Krikščionių partijos frakcija';
			case 'LCSF': return 'Liberalų ir centro sąjungos frakcija';
			case 'LDDP': return 'Lietuvos demokratinės darbo partijos frakcija';
			case 'LDF': return 'Liberaldemokratų frakcija';
			case 'LLRAF': return 'Lietuvos lenkų rinkimų akcijos frakcija';
			case 'LSF' : return 'Liberalų sąjūdžio frakcija';
			case 'LSDPF': return 'Lietuvos socialdemokratų partijos frakcija';
			case 'MG': return 'Mišri Seimo narių grupė';
			case 'MKDF': return 'Moderniųjų krikščionių demokratų frakcija';
			case 'NF': return 'Nepriklausoma frakcija';
			case 'NKF': return 'Nuosaikiųjų (konservatorių) frakcija';
			case 'NSF': return 'Naujosios sąjungos (socialliberalų) frakcija';
			case 'SDF': return 'Socialdemokratų frakcija';
			case 'SDF2000': return 'Frakcija "Socialdemokratija - 2000-ieji"';
			case 'SDKF': return 'Socialdemokratinės koalicijos (LDDP-LSDP-LRS) frakcija';
			case 'TSF': return 'Tėvynės sąjungos frakcija';
			case 'TSKF': return 'Tėvynės Sąjungos - konservatorių frakcija';
			case 'TSLK': return 'Tėvynės sąjungos - Lietuvos konservatorių (TS-LK) frakcija';
			case 'TSLKDF': return 'Tėvynės sąjungos - Lietuvos krikščionių demokratų frakcija';		
			case 'TTF': return 'Frakcija "Tvarka ir teisingumas"';
			case 'VLF': return 'Valstiečių liaudininkų frakcija';
			case 'VNDF': return 'Valstiečių ir Naujosios demokratijos partijų frakcija';
			default: return $fraction;
		}
	}

	function getPieColor($name) {
		switch ($name) {
			case 'abstain': return '#C78933';
			case 'reject': return '#C75233';
			case 'accept': return '#79B5AC';
			case 'disappeare': return '#5E2F46';
			case 'not presen': return '#D6CEAA';
		}
	}

	function niceVoteName($name) {
		switch ($name) {
			case 'accept': return 'Balsavo UŽ';
			case 'reject': return 'Balsavo PRIEŠ';
			case 'abstain': return 'Susilaikė';
			case 'not presen': return 'Neužsiregistravo';
			case 'disappeare': return 'Užsiregistravo, tačiau nebalsavo';
		}
	}

	function getMemberLink($id) {
		return cadencify('nariai/' . $id);
	}

	function cadencify($link) {
		if ((CADENCY == false) || (CADENCY == CURRENT_CADENCY)) {
			return $link;
		} else {
			return $link . '/' . CADENCY;
		}
	}
