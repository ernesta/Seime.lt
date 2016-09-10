<?php $start_time = microtime(true); ?>
<?php date_default_timezone_set('Europe/Zurich'); ?>
<?php require_once('frontend/includes/controller.php');	?>
<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/" lang="lt">

<?php require_once('frontend/components/head.php'); ?>

<body>
	<img style="display:none" src="frontend/resources/images/seime.png" alt="Seime.lt" />
	
	<div id="pagewidth" class="clearfix">
		<?php require_once('frontend/components/header.php'); ?>
		
		<div id="tabs">
			<?php getSiteNavigation(); ?>
		</div>
		
		<div id="content">
			<?php //getCadencyNavigation(); ?>
			<?php includePageTemplate(); ?>
		</div>
		
		<div id="shareTab">
			<a href="http://trumpai.seime.lt" title="Seime. Trumpai" target="_blank"><img src="frontend/resources/images/sharing/wordpress.png" alt="Seime. Trumpai"/></a>
			
			<a href="http://twitter.com/SeimeLT" title="Twitter: @SeimeLT" target="_blank"><img src="frontend/resources/images/sharing/twitter.png" alt="Twitter"/></a>

			<a href="https://www.facebook.com/Seime.lt" title="Facebook: Seime.lt" target="_blank"><img src="frontend/resources/images/sharing/facebook.png" alt="Facebook"/></a>
		</div>
		
		<div id="push"></div>
	</div>

	<?php require_once('frontend/components/footer.php'); ?>

	<?php foreach ($OBJECTS as $object) { $object->populateAll(); } ?>

	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery("a.fancy").fancybox({'padding': 20});
		});
	</script>
</body>
</html>

<?php
	$length = microtime(true) - $start_time;
	echo ' <!-- Load time: ' . round($length, 3) . ' -->';
	echo ' <!-- Debug: ' . implode("\n", MyLogger::$prints) . ' -->';
 ?>