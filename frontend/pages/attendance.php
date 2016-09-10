<div class="clearfix" id="attendance">

	<a id="top"></a>
	<div id="general-vote-one">
		<h3>Dirbtų posėdžių dalis (oficiali statistika)</h3>
		<?php echo getOfficialParticipationTop(); ?>
	</div>

	<div id="general-vote-two">
		<h3>Dirbtų valandų dalis</h3>
		<?php echo getParticipationTop(); ?>
	</div>

	<div id="general-vote-three">
		<h3>Dirbtų valandų dalies ir dirbtų posėdžių dalies santykis</h3>
		<?php echo getParticipationRatio(); ?>
	</div>

	<a id="lankomumas"></a>
	<div id="general-frac"></div>
	<div id="general-seas"></div>
	<div id="general-timeline"></div>

	<div class="footnote">* <?php echo getFractionList(); ?>.</div>
</div>
