<div class="clearfix" id="stats">
	<div id="individual-voting"></div>

	<div class="individual-stats">
		<span class="index"><?php echo getIndividualParticipation(); ?></span>
		<span class="explanation">Tokią visų posėdžių laiko dalį <?php echo MEMBER; ?> buvo Seime.</span>
	</div>

	<div class="individual-stats">
		<span class="index"><?php echo getOfficialParticipation(); ?></span>
		<span class="explanation">Oficialiais duomenimis, <?php echo MEMBER; ?> dalyvavo tokioje posėdžių dalyje.</span>
	</div>

	<div class="individual-stats id=individual-stats-three">
		<span class="index"><?php echo getIndividualVotingAbsolute('disappeare'); ?></span>
		<span class="explanation">Tiek kartų <?php echo MEMBER; ?> užsiregistravo balsavimui, tačiau savo balso neatidavė.</span>
	</div>

	<div id="individual-timeline"></div>
</div>

<div id="info">
	<img src="<?php echo getImage(MEMBER_ID); ?>" alt="<?php echo MEMBER; ?>" />
	<span id="name"><?php echo MEMBER; ?></span>
	<span id="fraction">
	<?php
		if ((MEMBER_END != '') && (MEMBER_START != '')) {
			echo 'Seime nuo ' . MEMBER_START . ' iki ' . MEMBER_END;
		}
		elseif (MEMBER_END != '') {
			echo 'Seime iki ' . MEMBER_END;
		}
		elseif (MEMBER_START != '') {
			echo getFractionName(MEMBER_FRACTION) . '<br/><br/>';
			echo 'Seime nuo ' . MEMBER_START;
		}
		else {
			echo getFractionName(MEMBER_FRACTION);
		}
		if (MEMBER_NOTES != '') echo '<br/><br/>' . MEMBER_NOTES;
	?>
	</span>
</div>