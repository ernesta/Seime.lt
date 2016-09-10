<div class="clearfix" id="voting">
	<a id="top"></a>
	<div id="general-vote-one">
		<?php 
			$a = array(array('title' => 'Balsavo UŽ', 'data' => 'accept'), array('title' => 'Balsavo PRIEŠ', 'data' => 'reject'), array('title' => ''));
			$i = rand(0,1);
			$used = $a[$i];
		?>
		<h3><?php echo $used['title']; ?></h3>
		<?php echo getVotingTop($used['data']); ?>
	</div>
	
	<?php
		unset($a);
		unset($i);
		unset($used);
	?>
	
	<div id="general-vote-two">
		<h3>Susilaikė</h3>
		<?php echo getVotingTop('abstain'); ?>
	</div>

	<div id="general-vote-three">
		<h3>Užsiregistravo, bet nebalsavo</h3>
		<?php echo getNovotingTop('disappeare'); ?>
	</div>

	<a id="balsavimas"></a>
	<div id="general-vdistr"></div>
	<div id="general-vover"></div>

	<div id="general-forgainst"></div>
	<div id="general-forreject"></div>
</div>